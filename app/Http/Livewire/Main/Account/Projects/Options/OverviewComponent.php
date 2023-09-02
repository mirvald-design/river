<?php

namespace App\Http\Livewire\Main\Account\Projects\Options;

use App\Http\Validators\Main\Account\Projects\Employer\MilestoneValidator;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\User;
use Livewire\Component;
use WireUi\Traits\Actions;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;

class OverviewComponent extends Component
{
    use SEOToolsTrait, Actions;
    
    public $project;
    public $paid_amount;

    // Milestone form
    public $milestone_amount;
    public $milestone_description;

    /**
     * Init component
     *
     * @param string $id
     * @return void
     */
    public function mount($id)
    {
        // Get project
        $project = Project::where('uid', $id)
                            ->withCount('bids')
                            ->with('milestones')
                            ->where('user_id', auth()->id())
                            ->firstOrFail();

        // Set project
        $this->project = $project;

        // Calculate paid amount
        $this->calculatePaidAmount();
    }


    /**
     * Render component
     *
     * @return Illuminate\View\View
     */
    public function render()
    {
        // SEO
        $separator   = settings('general')->separator;
        $title       = __('messages.t_project_overview') . " $separator " . settings('general')->title;
        $description = settings('seo')->description;
        $ogimage     = src( settings('seo')->ogimage );

        $this->seo()->setTitle( $title );
        $this->seo()->setDescription( $description );
        $this->seo()->setCanonical( url()->current() );
        $this->seo()->opengraph()->setTitle( $title );
        $this->seo()->opengraph()->setDescription( $description );
        $this->seo()->opengraph()->setUrl( url()->current() );
        $this->seo()->opengraph()->setType('website');
        $this->seo()->opengraph()->addImage( $ogimage );
        $this->seo()->twitter()->setImage( $ogimage );
        $this->seo()->twitter()->setUrl( url()->current() );
        $this->seo()->twitter()->setSite( "@" . settings('seo')->twitter_username );
        $this->seo()->twitter()->addValue('card', 'summary_large_image');
        $this->seo()->metatags()->addMeta('fb:page_id', settings('seo')->facebook_page_id, 'property');
        $this->seo()->metatags()->addMeta('fb:app_id', settings('seo')->facebook_app_id, 'property');
        $this->seo()->metatags()->addMeta('robots', 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1', 'name');
        $this->seo()->jsonLd()->setTitle( $title );
        $this->seo()->jsonLd()->setDescription( $description );
        $this->seo()->jsonLd()->setUrl( url()->current() );
        $this->seo()->jsonLd()->setType('WebSite');

        return view('livewire.main.account.projects.options.overview')->extends('livewire.main.layout.app')->section('content');
    }


    /**
     * Calculate paid amount
     *
     * @return int
     */
    private function calculatePaidAmount()
    {
        // Check if project has an awarded bid
        if ($this->project->awarded_bid) {
            
            // Let's calculate paid amount
            $this->paid_amount = ProjectMilestone::where('project_id', $this->project->id)->where('status', 'paid')->sum('amount');

        } else {
           
            // Not paid amount yet
            $this->paid_amount = 0;

        }
    }


    /**
     * Create a milestone payment
     *
     * @return mixed
     */
    public function milestone()
    {
        try {
            
            // Validate form
            MilestoneValidator::validate($this);

            // Project must be active
            if (!in_array($this->project->status, ['active', 'under_development', 'pending_final_review'])) {
                
                // Error
                $this->notification([
                    'title'       => __('messages.t_error'),
                    'description' => __('messages.t_u_cannot_create_milestones_for_this_project'),
                    'icon'        => 'error'
                ]);

                return;

            }

            // Check if employer has this money
            if ($this->milestone_amount > auth()->user()->balance_available) {
                
                // Error
                $this->notification([
                    'title'       => __('messages.t_error'),
                    'description' => __('messages.t_employer_u_dont_have_milestone_amount'),
                    'icon'        => 'error'
                ]);

                return;

            }

            // Create new milestone
            $milestone              = new ProjectMilestone();
            $milestone->uid         = uid();
            $milestone->project_id  = $this->project->id;
            $milestone->created_by  = 'employer';
            $milestone->employer_id = auth()->id();
            $milestone->amount      = $this->milestone_amount;
            $milestone->description = $this->milestone_description;
            $milestone->status      = 'funded';
            $milestone->save();

            // Let's update user available balance
            User::where('id', auth()->id())->update([
                'balance_available' => auth()->user()->balance_available - $this->milestone_amount
            ]);

            // Reset form
            $this->reset(['milestone_amount', 'milestone_description']);

            // Close modal
            $this->dispatchBrowserEvent('close-modal', 'modal-create-milestone-container-' . $this->project->uid);

            // Refresh project
            $this->project->refresh();

            // Success
            $this->notification([
                'title'       => __('messages.t_success'),
                'description' => __('messages.t_milestone_created_success'),
                'icon'        => 'success'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {

            // Validation error
            $this->notification([
                'title'       => __('messages.t_error'),
                'description' => __('messages.t_toast_form_validation_error'),
                'icon'        => 'error'
            ]);

            throw $e;

        } catch (\Throwable $th) {

            // Error
            $this->notification([
                'title'       => __('messages.t_error'),
                'description' => $th->getMessage(),
                'icon'        => 'error'
            ]);

        }
    }


    /**
     * Release milestone payment
     *
     * @param int $id
     * @return mixed
     */
    public function release($id)
    {
        try {
            
            // Get milestone
            $milestone = ProjectMilestone::where('id', $id)
                                        ->where('status', 'funded')
                                        ->with('project')
                                        ->with('project.awarded_bid')
                                        ->firstOrFail();

            // Release this payment
            $milestone->status = 'paid';
            $milestone->save();

            // Calculate paid amount
            $this->calculatePaidAmount();

            // Give freelancer his money
            if ($milestone->project?->awarded_bid) {
                
                // Get freelancer
                $freelancer = $milestone->project?->awarded_bid->user;

                // Update freelancer's amount
                User::where('id', $freelancer->id)->update([
                    'balance_available' => $freelancer->balance_available + $milestone->amount
                ]);

                // We have to mark this project as completed if budget received
                if ($this->paid_amount >= $milestone->project?->awarded_bid->amount) {
                    
                    // Update project
                    $this->project->status = 'completed';
                    $this->project->save();

                }

            }
            
            // Refresh project
            $this->project->refresh();

            // Close modal
            $this->dispatchBrowserEvent('close-modal', 'modal-release-payment-container-' . $milestone->uid);

            // Success
            $this->notification([
                'title'       => __('messages.t_success'),
                'description' => __('messages.t_milestone_payment_released_success'),
                'icon'        => 'success'
            ]);

        } catch (\Throwable $th) {
            
            // Error
            $this->notification([
                'title'       => __('messages.t_error'),
                'description' => $th->getMessage(),
                'icon'        => 'error'
            ]);

        }
    }
    
}
