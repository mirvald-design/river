<?php

namespace App\Http\Livewire\Main\Account\Bids\Options;

use Livewire\Component;
use WireUi\Traits\Actions;
use App\Models\ProjectPlan;
use App\Models\ProjectBidUpgrade;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Jobs\Main\Post\Project\SendAlertToFreelancers;
use App\Models\ProjectBid;
use App\Models\ProjectBiddingPlan;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;

class CheckoutComponent extends Component
{
    use SEOToolsTrait, Actions;
    
    public $subscription;
    public $selected_payment_method;

    /**
     * Init component
     *
     * @param string $id
     * @return void
     */
    public function mount($id)
    {
        // Get subscription
        $subscription       = ProjectBidUpgrade::where('uid', $id)
                                                ->with('bid', function($query) {
                                                    return $query->where('user_id', auth()->id())
                                                                 ->where('status', 'pending_payment')
                                                                 ->with('project');
                                                })
                                                ->whereStatus('pending')
                                                ->firstOrFail();

        // Check if this project has not awarded bid yet
        if ($subscription->bid?->project?->awarded_bid) {

            // Go back to bids list
            return redirect('account/bids')->with('error', __('messages.t_projet_already_awarded_u_cant_promote_bid'));

        }

        // So, there is no awarded bid yet, but we need to check another thing
        // What if he selected a sponsored bid plan, and someone already paid it before him
        // In this case we have to remove the sponsored plan from his order
        if ($subscription->bid?->is_sponsored) {
            
            // Get another sponsored bid
            $already_sponsored = ProjectBid::whereProjectId($subscription->bid?->project_id)
                                            ->whereIsSponsored(true)
                                            ->whereIn('status', ['active', 'pending_payment'])
                                            ->where('id', '!=', $subscription->bid?->id)
                                            ->first();

            // Check if it exists
            if ($already_sponsored) {
                
                // Let's get the sponsored plan
                $sponsored_plan = ProjectBiddingPlan::wherePlanType('sponsored')->first();

                // We have to make sure, the sponsored plan is exists in database, to prevent errors
                if ($sponsored_plan) {
                    
                    // You see, we got a sponsored bid, let's update this current bid
                    $subscription->bid()?->update([
                        'is_sponsored' => false
                    ]);

                    // Now, let's update this subscription and remove the sponsored plan price from the total amount
                    $subscription->amount = $subscription->amount - $sponsored_plan->price;
                    $subscription->save();

                    // Refresh this subscription
                    $subscription->refresh();

                }

            }

        }

        // Set subscription
        $this->subscription = $subscription;
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
        $title       = __('messages.t_promote_bid') . " $separator " . settings('general')->title;
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

        return view('livewire.main.account.bids.options.checkout')->extends('livewire.main.layout.app')->section('content');
    }


    /**
     * Checkout
     *
     * @param mixed $data
     * @return void
     */
    public function checkout($data = null)
    {
        try {
            
            // Check payment method
            switch ($this->selected_payment_method) {

                // PayPal
                case 'paypal':

                    // Get payment gateway exchange rate
                    $gateway_currency_exchange = (float)settings('paypal')->exchange_rate;

                    // Get total amount
                    $total_amount              = $this->calculateExchangeAmount($gateway_currency_exchange);

                    // Set paypal provider and config
                    $client                    = new PayPalClient();
            
                    // Get paypal access token
                    $client->getAccessToken();

                    // Capture this order
                    $order                     = $client->capturePaymentOrder($data);

                    // Let's see if payment suuceeded
                    if ( is_array($order) && isset($order['status']) && $order['status'] === 'COMPLETED' ) {
                        
                        // Get paid amount
                        $amount   = $order['purchase_units'][0]['payments']['captures'][0]['amount']['value'];

                        // Get currency
                        $currency = $order['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'];

                        // Check currency
                        if (strtolower($currency) != strtolower(config('paypal.currency'))) {
                            
                            // Error
                            $this->notification([
                                'title'       => __('messages.t_error'),
                                'description' => __('messages.t_checkout_currency_invalid'),
                                'icon'        => 'error'
                            ]);

                            return;

                        }

                        // This amount must equals amount in order
                        if ($amount != $total_amount) {
                            
                            // Error
                            $this->notification([
                                'title'       => __('messages.t_error'),
                                'description' => __('messages.t_amount_in_cart_not_equals_received'),
                                'icon'        => 'error'
                            ]);

                            return;

                        }

                        // Successfull payment
                        $this->success('paypal', $order['id']);

                        // Go to bids page
                        return redirect('account/bids')->with('success', __('messages.t_ur_payment_has_succeeded'));

                    } else {

                        // We couldn't handle your payment
                        $this->notification([
                            'title'       => __('messages.t_error'),
                            'description' => __('messages.t_we_could_not_handle_ur_payment'),
                            'icon'        => 'error'
                        ]);

                        return;

                    }

                    break;
                
                default:
                    
                    // No payment selected
                    $this->notification([
                        'title'       => __('messages.t_error'),
                        'description' => __('messages.t_select_payment_method'),
                        'icon'        => 'error'
                    ]);

                    break;
            }

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
     * Calculate exchange rate
     *
     * @param float $gateway_exchange_rate
     * @return mixed
     */
    protected function calculateExchangeAmount($gateway_exchange_rate = null)
    {
        try {
            
            // Get total amount
            $amount                = $this->subscription->amount;

            // Get default currency exchange rate
            $default_exchange_rate = (float) settings('currency')->exchange_rate;

            // Set gateway exchange rate
            $gateway_exchange_rate = is_null($gateway_exchange_rate) ? $default_exchange_rate : (float) $gateway_exchange_rate;
            
            // Check if same exchange rate
            if ($default_exchange_rate == $gateway_exchange_rate) {
                
                // No need to calculate amount
                return $amount;

            } else {

                // Return new amount
                return (float)number_format( $amount * $gateway_exchange_rate / $default_exchange_rate, 2, '.', '');

            }

        } catch (\Throwable $th) {
            return $amount;
        }
    }


    /**
     * Successfull payment
     *
     * @param string $payment_method
     * @param string $payment_id
     * @return void
     */
    private function success($payment_method, $payment_id)
    {
        
        // Get bid
        $bid                                = $this->subscription->bid;

        // Get projects settings
        $settings                           = settings('projects');

        // Update subscription
        $this->subscription->payment_method = $payment_method;
        $this->subscription->payment_id     = $payment_id;
        $this->subscription->status         = 'paid';
        $this->subscription->save();

        // Update bid
        $bid->status                        = $settings->auto_approve_bids ? 'active' : 'pending_approval';
        $bid->save();

    }

}
