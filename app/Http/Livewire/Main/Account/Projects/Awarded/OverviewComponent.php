<?php

namespace App\Http\Livewire\Main\Account\Projects\Awarded;

use App\Models\Project;
use Livewire\Component;
use WireUi\Traits\Actions;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;

class OverviewComponent extends Component
{
    use SEOToolsTrait, Actions;
    
    public $project;

    /**
     * Init component
     *
     * @param int $pid
     * @param string $slug
     * @return void
     */
    public function mount($pid, $slug)
    {
        // Get project
        $project = Project::where('pid', $pid)
                            ->where('slug', $slug)
                            ->whereIn('status', ['active', 'completed', 'under_development', 'pending_final_review', 'incomplete', 'closed'])
                            ->whereHas('awarded_bid', function($query) {
                                return $query->where('user_id', auth()->id())
                                                ->where('is_freelancer_accepted', true)
                                                ->where('status', 'active');
                            })
                            ->first();
        
        // Check if this project exists
        if (!$project) {
            
            // No project found
            return redirect('account/projects/awarded')->with('error', __('messages.t_no_project_found_in_our_records'));

        }

        // Set project
        $this->project = $project;
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

        return view('livewire.main.account.projects.awarded.overview')->extends('livewire.main.layout.app')->section('content');
    }
    
}
