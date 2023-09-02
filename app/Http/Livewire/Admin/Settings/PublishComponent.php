<?php

namespace App\Http\Livewire\Admin\Settings;

use Livewire\Component;
use WireUi\Traits\Actions;
use App\Models\SettingsPublish;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use App\Http\Validators\Admin\Settings\PublishValidator;

class PublishComponent extends Component
{
    use SEOToolsTrait, Actions;
    
    public $auto_approve_gigs;
    public $auto_approve_portfolio;
    public $max_tags;
    public $is_video_enabled;
    public $is_documents_enabled;
    public $max_documents;
    public $max_document_size;
    public $max_images;
    public $max_image_size;

    /**
     * Initialize component
     *
     * @return void
     */
    public function mount()
    {
        // Get settings
        $settings = settings('publish');

        // Fill default settings
        $this->fill([
            'auto_approve_gigs'      => $settings->auto_approve_gigs ? 1 : 0,
            'auto_approve_portfolio' => $settings->auto_approve_portfolio ? 1 : 0,
            'max_tags'               => $settings->max_tags,
            'is_video_enabled'       => $settings->is_video_enabled ? 1 : 0,
            'is_documents_enabled'   => $settings->is_documents_enabled ? 1 : 0,
            'max_documents'          => $settings->max_documents,
            'max_document_size'      => $settings->max_document_size,
            'max_images'             => $settings->max_images,
            'max_image_size'         => $settings->max_image_size
        ]);
    }


    /**
     * Render component
     *
     * @return Illuminate\View\View
     */
    public function render()
    {
        // Seo
        $this->seo()->setTitle( setSeoTitle(__('messages.t_publish_settings'), true) );
        $this->seo()->setDescription( settings('seo')->description );

        return view('livewire.admin.settings.publish')->extends('livewire.admin.layout.app')->section('content');
    }


    /**
     * Update settings
     *
     * @return void
     */
    public function update()
    {
        try {

            // Validate form
            PublishValidator::validate($this);

            // Update settings
            SettingsPublish::where('id', 1)->update([
                'auto_approve_gigs'      => $this->auto_approve_gigs ? 1 : 0,
                'auto_approve_portfolio' => $this->auto_approve_portfolio ? 1 : 0,
                'max_tags'               => $this->max_tags,
                'is_video_enabled'       => $this->is_video_enabled ? 1 : 0,
                'is_documents_enabled'   => $this->is_documents_enabled ? 1 : 0,
                'max_documents'          => $this->max_documents,
                'max_document_size'      => $this->max_document_size,
                'max_images'             => $this->max_images,
                'max_image_size'         => $this->max_image_size
            ]);

            // Refresh data from cache
            settings('publish', true);

            // Success
            $this->notification([
                'title'       => __('messages.t_success'),
                'description' => __('messages.t_toast_operation_success'),
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
                'description' => __('messages.t_toast_something_went_wrong'),
                'icon'        => 'error'
            ]);

            throw $th;

        }
    }
    
}
