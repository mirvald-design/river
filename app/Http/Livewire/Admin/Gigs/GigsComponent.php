<?php

namespace App\Http\Livewire\Admin\Gigs;

use App\Models\Gig;
use Livewire\Component;
use WireUi\Traits\Actions;
use Livewire\WithPagination;
use App\Notifications\User\Everyone\GigPublished;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;

class GigsComponent extends Component
{
    use WithPagination, SEOToolsTrait, Actions;

    /**
     * Render component
     *
     * @return Illuminate\View\View
     */
    public function render()
    {
        // Seo
        $this->seo()->setTitle( setSeoTitle(__('messages.t_gigs'), true) );
        $this->seo()->setDescription( settings('seo')->description );

        return view('livewire.admin.gigs.gigs', [
            'gigs' => $this->gigs
        ])->extends('livewire.admin.layout.app')->section('content');
    }


    /**
     * Get list of gigs
     *
     * @return object
     */
    public function getGigsProperty()
    {
        return Gig::latest()->paginate(42);
    }


    /**
     * Delete gig
     *
     * @param integer $id
     * @return void
     */
    public function delete($id)
    {
        // Get gig
        $gig = Gig::where('id', $id)->firstOrFail();

        // Check of gig has pending orders
        if ($gig->orders_in_queue) {
            
            // You can't delete this
            $this->notification([
                'title'       => __('messages.t_error'),
                'description' => __('messages.t_u_cant_delete_this_gig_pending_orders'),
                'icon'        => 'error'
            ]);

            return;

        }

        // Delete it
        $gig->delete();

        // success
        $this->notification([
            'title'       => __('messages.t_success'),
            'description' => __('messages.t_gig_deleted_successfull'),
            'icon'        => 'success'
        ]);
    }


    /**
     * Publish gig
     *
     * @param integer $id
     * @return void
     */
    public function publish($id)
    {
        // Get gig
        $gig = Gig::where('id', $id)->where('status', 'pending')->firstOrFail();

        // Activate gig
        $gig->status = 'active';
        $gig->save();

        // Send notification to owner
        $gig->owner->notify( (new GigPublished($gig))->locale(config('app.locale')) );

        // Send notification
        notification([
            'text'    => 't_ur_gig_title_has_been_published',
            'action'  => url('service', $gig->slug),
            'user_id' => $gig->user_id,
            'params'  => ['title' => $gig->title]
        ]);

        // success
        $this->notification([
            'title'       => __('messages.t_success'),
            'description' => __('messages.t_gig_published_success'),
            'icon'        => 'success'
        ]);
    }

}
