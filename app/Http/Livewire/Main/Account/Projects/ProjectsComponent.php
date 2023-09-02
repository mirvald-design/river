<?php

namespace App\Http\Livewire\Main\Account\Projects;

use App\Models\Project;
use Livewire\Component;
use Livewire\WithPagination;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use WireUi\Traits\Actions;

class ProjectsComponent extends Component
{

    use WithPagination, SEOToolsTrait, Actions;

    /**
     * Initialize component
     *
     * @return mixed
     */
    public function mount()
    {
        // Get current user
        $user = auth()->user();

        // Get settings
        $settings = settings('projects');

        // Check if this section enabled
        if (!$settings->is_enabled) {
        
            // Redirect to home page
            return redirect('/');

        }

        // Check if current user's account type allowed for projects section
        if ($settings->who_can_post !== 'both' && $settings->who_can_post !== $user->account_type) {
            
            // Redirect to home page
            return redirect('/');

        }
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
        $title       = __('messages.t_my_projects') . " $separator " . settings('general')->title;
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

        return view('livewire.main.account.projects.projects', [
            'projects' => $this->projects
        ])->extends('livewire.main.layout.app')->section('content');
    }


    /**
     * Get list of projects
     *
     * @return object
     */
    public function getProjectsProperty()
    {
        return Project::where('user_id', auth()->id())->latest()->paginate(42);
    }


    /**
     * Confirm deleting project
     *
     * @param string $id
     * @return mixed
     */
    public function confirmDelete($id)
    {
        // Get project
        $project = Project::whereUid($id)->whereUserId(auth()->id())->firstOrFail();

        // Confirm delete
        $this->dialog()->confirm([
            'title'       => __('messages.t_confirm_delete'),
            'description' => "<div class='leading-relaxed'>" . __('messages.t_are_u_sure_u_want_to_delete_this_project') . "<br><b>". $project->title ."</b><br>" . __('messages.t_all_records_related_to_project_will_erased') . "</div>",
            'icon'        => 'error',
            'accept'      => [
                'label'  => __('messages.t_delete'),
                'method' => 'delete',
                'params' => $project->uid,
            ],
            'reject' => [
                'label'  => __('messages.t_cancel')
            ],
        ]);
    }


    /**
     * Delete project
     *
     * @param string $id
     * @return mixed
     */
    public function delete($id)
    {
        // Get project
        $project = Project::whereUid($id)->whereUserId(auth()->id())->firstOrFail();

        // Success
        $this->notification([
            'title'       => __('messages.t_success'),
            'description' => __('messages.t_toast_operation_success'),
            'icon'        => 'success'
        ]);
    }
    
}