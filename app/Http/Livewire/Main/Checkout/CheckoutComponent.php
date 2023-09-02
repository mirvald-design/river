<?php

namespace App\Http\Livewire\Main\Checkout;

use DateTime;
use DateTimeZone;
use Xendit\Cards;
use Xendit\Xendit;
use App\Models\Gig;
use App\Models\Admin;
use App\Models\Order;
use Razorpay\Api\Api;
use Livewire\Component;
use App\Models\OrderItem;
use YouCan\Pay\YouCanPay;
use App\Models\GigUpgrade;
use WireUi\Traits\Actions;
use App\Models\UserBilling;
use Illuminate\Support\Str;
use App\Models\OrderInvoice;
use Cartalyst\Stripe\Stripe;
use App\Models\CheckoutWebhook;
use App\Models\OrderItemUpgrade;
use Illuminate\Support\Facades\Http;
use App\Notifications\User\Buyer\OrderPlaced;
use Unicodeveloper\Paystack\Facades\Paystack;
use App\Notifications\User\Seller\PendingOrder;
use Paytabscom\Laravel_paytabs\Facades\paypage;
use App\Notifications\Admin\PendingOfflinePayment;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use LoveyCom\CashFree\PaymentGateway\Order as CashfreeOrder;

class CheckoutComponent extends Component
{
    use SEOToolsTrait, Actions;
    
    public $cart;

    public $payment_method = null;

    // Billing
    public $firstname;
    public $lastname;
    public $email;
    public $company;
    public $address;

    // Stripe
    public $stripe_intent_secret;

    // Paymob
    public $paymob_payment_token;
    public $paymob_phone;
    public $paymob_firstname;
    public $paymob_lastname;

    // Razorpay
    public $razorpay_order_id;

    // NowPayments.io
    public $nowpayments_pay_address;
    public $nowpayments_payment_id;
    public $nowpayments_payment_status;
    public $nowpayments_price_amount;
    public $nowpayments_pay_amount;

    // Errors
    public $has_error     = false;
    public $error_message = null;

    protected $listeners = ['cart-updated' => 'cartUpdated'];

    /**
     * Init component
     *
     * @return void
     */
    public function mount()
    {
        // We have to validate the cart
        // How? For example if user is not logged in, he may be able to add his own gigs to cart and the login to checkout
        // So we need to remove his own gigs from cart after login
        $this->validateCart();

        // Get cart
        $cart = session('cart', []);

        // Check if cart has items
        if (is_array($cart) && count($cart)) {
            
            // Set cart
            $this->cart            = $cart;

            // Get user billing
            $billing               = UserBilling::firstOrCreate(['user_id' => auth()->id()]);
            
            // Set billing info
            $this->firstname       = $billing->firstname;
            $this->lastname        = $billing->lastname;
            $this->email           = auth()->user()->email;
            $this->company         = $billing->company;
            $this->address         = $billing->address;

        } else {

            // Cart has no items
            return redirect('cart');

        }

        // Initialize Stripe
        if (settings('stripe')->is_enabled) {
            $this->initStripe();
        }

        // Check if razorpay enabled
        if (settings('razorpay')->is_enabled) {
            $this->initRazorpay();
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
        $title       = __('messages.t_checkout') . " $separator " . settings('general')->title;
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

        return view('livewire.main.checkout.checkout')->extends('livewire.main.layout.app')->section('content');
    }


    /**
     * Check selected payment method
     *
     * @return void
     */
    public function updatedPaymentMethod()
    {
        // Generate payment for NowPayments.io
        if ($this->payment_method === 'nowpayments' && settings('nowpayments')->is_enabled) {
            
            try {
                
                $client  = new Client();
                $headers = [
                    'x-api-key'    => config('nowpayments.api_key'),
                    'Content-Type' => 'application/json'
                ];
                // Get payment gateway exchange rate
                $gateway_currency_exchange = (float) settings('nowpayments')->exchange_rate;
                
                // Set total price
                $total_amount              = $this->calculateExchangeAmount($gateway_currency_exchange);

                $body = [
                    "price_amount"      => $total_amount,
                    "price_currency"    => settings('nowpayments')->currency,
                    "pay_currency"      => settings('nowpayments')->crypto_currency,
                    "ipn_callback_url"  => "https://nowpayments.io",
                    "order_id"          => uid(),
                    "order_description" => __('messages.t_checkout')
                ];
                
                $request = new Request('POST', config('nowpayments.payment_url'), $headers, json_encode($body));
                $res     = $client->sendAsync($request)->wait();
                $data    = json_decode($res->getBody(), true);
                
                // Set data
                if (is_array($data) && isset($data['success']) && $data['success'] === 'success') {
                    
                    $this->nowpayments_pay_address    = $data['pay_address'];
                    $this->nowpayments_payment_id     = $data['payment_id'];
                    $this->nowpayments_payment_status = $data['payment_status'];
                    $this->nowpayments_price_amount   = $data['price_amount'];
                    $this->nowpayments_pay_amount     = $data['pay_amount'];

                } else {

                    // Something went wrong
                    $this->notification([
                        'title'       => __('messages.t_error'),
                        'description' => __('messages.t_toast_something_went_wrong'),
                        'icon'        => 'error'
                    ]);

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
    }


    /**
     * Count total price of an item in cart
     *
     * @param string $id
     * @return integer
     */
    public function itemTotalPrice($id)
    {
        // Set empty var total price
        $total = 0;

        // Loop throug items in cart
        foreach ($this->cart as $key => $item) {
            
            // Check if item exists
            if ($item['id'] === $id) {
                
                // Get quantity
                $quantity = (int) $item['quantity'];

                // Sum upgrades total price
                if (is_array($item['upgrades']) && count($item['upgrades'])) {
                    
                    $total_upgrades_price = array_reduce($item['upgrades'], function($i, $obj)
                    {
                        // Calculate only selected upgrades
                        if ($obj['checked'] == true) {
                            return $i += $obj['price'];
                        } else {
                            return $i;
                        }

                    });

                } else {

                    // No upgrades selected
                    $total_upgrades_price = 0;

                }

                // Set new total
                $total = ($quantity * $item['gig']['price']) + ($total_upgrades_price * $quantity);

            }

        }

        // Return total price
        return $total;

    }


    /**
     * Calculate subtotal price
     *
     * @return integer
     */
    public function subtotal()
    {
        // Calculate subtotal
        $subtotal = $this->total();

        // Return subtotal
        return $subtotal;
    }


    /**
     * Calculate taxes
     *
     * @return integer
     */
    public function taxes()
    {
        // Get commission settings
        $settings = settings('commission');

        // Check if taxes enabled
        if ($settings->enable_taxes) {
            
            // Check if type of taxes percentage
            if ($settings->tax_type === 'percentage') {
                
                // Get tax amount
                $tax = bcmul($this->total(), $settings->tax_value) / 100;

                // Return tax amount
                return $tax;

            } else {
                
                // Fixed price
                $tax = $settings->tax_value;

                // Return tax
                return $tax;

            }

        } else {

            // Taxes not enabled
            return 0;

        }
    }


    /**
     * Calculate total price
     *
     * @return integer
     */
    public function total()
    {
        // Set total empty variable
        $total = 0;

        // Loop through items in cart
        foreach ($this->cart as $key => $item) {
            
            // Update total price
            $total += $this->itemTotalPrice($item['id']);

        }

        // Return total price
        return $total;
    }


    /**
     * Calculate commission
     *
     * @param string $price
     * @return integer
     */
    public function commission($price)
    {
        // Get settings
        $settings = settings('commission');

        // Commission percentage
        if ($settings->commission_type === 'percentage') {
            
            // Calculate commission
            $commission = $settings->commission_value * $price / 100;

        } else {

            // Fixed amount
            $commission = $settings->commission_value;

        }

        // Return commission
        return $commission;
    }


    /**
     * Place order now
     *
     * @return void
     */
    public function checkout($options = null)
    {
        try {

            // Get allowed payment gateways
            $supported_payment_gateways = [
                'paypal'      => settings('paypal')->is_enabled,
                'stripe'      => settings('stripe')->is_enabled,
                'wallet'      => auth()->user()->balance_available >= $this->total() + $this->taxes(),
                'offline'     => settings('offline_payment')->is_enabled,
                'flutterwave' => settings('flutterwave')->is_enabled,
                'paystack'    => settings('paystack')->is_enabled,
                'cashfree'    => settings('cashfree')->is_enabled,
                'mollie'      => settings('mollie')->is_enabled,
                'xendit'      => settings('xendit')->is_enabled,
                'mercadopago' => settings('mercadopago')->is_enabled,
                'vnpay'       => settings('vnpay')->is_enabled,
                'paymob'      => settings('paymob')->is_enabled,
                'paytabs'     => settings('paytabs')->is_enabled,
                'paytr'       => settings('paytr')->is_enabled,
                'razorpay'    => settings('razorpay')->is_enabled,
                'jazzcash'    => settings('jazzcash')->is_enabled,
                'youcanpay'   => settings('youcanpay')->is_enabled,
                'nowpayments' => settings('nowpayments')->is_enabled
            ];

            // Payment gateway is required
            if ( !array_key_exists($this->payment_method, $supported_payment_gateways) || !isset($supported_payment_gateways[$this->payment_method]) || !$supported_payment_gateways[$this->payment_method] ) {
               
                // Error
                $this->notification([
                    'title'       => __('messages.t_error'),
                    'description' => __('messages.t_please_choose_a_payment_method'),
                    'icon'        => 'error'
                ]);

                // Return
                return;

            }

            // Check selected payment gateway
            switch ($this->payment_method) {

                // Paypal
                case 'paypal':
                    
                    // Get response
                    $response = $this->paypal($options);

                    break;

                // Wallet
                case 'wallet':
                    
                    // Get response
                    $response = $this->wallet();

                    break;

                // Offline
                case 'offline':
                    
                    // Set response
                    $response = [
                        'success'     => true,
                        'transaction' => [
                            'payment_id'     => uid(),
                            'payment_method' => 'offline',
                            'payment_status' => 'pending'
                        ]
                    ];

                    break;

                // Paystack
                case 'paystack':
                    
                    // Get response
                    $response = $this->paystack($options);

                    break;

                // Cashfree
                case 'cashfree':
                    
                    // Get response
                    $response = $this->cashfree($options);

                    break;

                // Mollie
                case 'mollie':

                    // Get payment gateway exchange rate
                    $gateway_currency_exchange = (float)settings('mollie')->exchange_rate;

                    // Get payment gateway currency
                    $gateway_currency_code     = settings('mollie')->currency;

                    // Get total amount
                    $total_amount              = number_format( $this->calculateExchangeAmount($gateway_currency_exchange), 2, '.', '' );

                    // Set mollie client
                    $mollie                    = new \Mollie\Api\MollieApiClient();

                    // Set api key
                    $mollie->setApiKey(config('mollie.key'));

                    // Create a payment request
                    $payment  = $mollie->payments->create([
                        "amount" => [
                            "currency" => "$gateway_currency_code",
                            "value"    => "$total_amount"
                        ],
                        "description" => __('messages.t_checkout'),
                        "redirectUrl" => url("checkout/callback/mollie/redirect"),
                        "webhookUrl"  => url("checkout/callback/mollie/webhook")
                    ]);

                    // Redirect to payment link
                    return redirect($payment->getCheckoutUrl());

                    break;

                // Xendit
                case 'xendit':
                    
                    // Get response
                    $response = $this->xendit($options);

                    break;

                // Mercadopago
                case 'mercadopago':
                    
                    // Get response
                    $response = $this->mercadopago($options);

                    break;

                // Vnpay
                case 'vnpay':

                    // Set timezone
                    $tz                        = 'Asia/Ho_Chi_Minh';
                    $timestamp                 = time();
                    $dt                        = new DateTime("now", new DateTimeZone($tz));
                    $dt->setTimestamp($timestamp);
                    $startTime                 = $dt->format('YmdHis');

                    // Get payment gateway exchange rate
                    $gateway_currency_exchange = (float)settings('vnpay')->exchange_rate;

                    // Set payment gateway settings
                    $vnp_TmnCode               = config('vnpay.tmn_code');
                    $vnp_HashSecret            = config('vnpay.hash_secret');
                    $vnp_Url                   = config('vnpay.api_url');
                    $vnp_Returnurl             = url('checkout/callback/vnpay');

                    // Set order details
                    $vnp_TxnRef                = uid();
                    $vnp_OrderInfo             = __('messages.t_checkout');
                    $vnp_OrderType             = "other";
                    $vnp_Amount                = $this->calculateExchangeAmount($gateway_currency_exchange) * 100;
                    $vnp_Locale                = app()->getLocale() == 'en' ? "en" : "vn";
                    $vnp_IpAddr                = request()->ip();
                    $vnp_ExpireDate            = date('YmdHis',strtotime('+15 minutes',strtotime($startTime)));

                    // Set data
                    $inputData                 = array(
                        "vnp_Version"        => "2.1.0",
                        "vnp_TmnCode"        => $vnp_TmnCode,
                        "vnp_Amount"         => $vnp_Amount,
                        "vnp_Command"        => "pay",
                        "vnp_CreateDate"     => date('YmdHis'),
                        "vnp_CurrCode"       => settings('vnpay')->currency,
                        "vnp_IpAddr"         => $vnp_IpAddr,
                        "vnp_Locale"         => $vnp_Locale,
                        "vnp_OrderInfo"      => $vnp_OrderInfo,
                        "vnp_OrderType"      => $vnp_OrderType,
                        "vnp_ReturnUrl"      => $vnp_Returnurl,
                        "vnp_TxnRef"         => $vnp_TxnRef,
                        "vnp_ExpireDate"     => $vnp_ExpireDate
                    );

                    ksort($inputData);
                    $query    = "";
                    $i        = 0;
                    $hashdata = "";

                    foreach ($inputData as $key => $value) {
                        if ($i == 1) {
                            $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                        } else {
                            $hashdata .= urlencode($key) . "=" . urlencode($value);
                            $i = 1;
                        }
                        $query .= urlencode($key) . "=" . urlencode($value) . '&';
                    }

                    // Set payment url
                    $vnp_Url = $vnp_Url . "?" . $query;

                    // Generate secure hash
                    if (isset($vnp_HashSecret)) {
                        $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret);//  
                        $vnp_Url      .= 'vnp_SecureHash=' . $vnpSecureHash;
                    }

                    // Go to payment url
                    return redirect($vnp_Url);
                    
                    break;

                // Paytabs
                case 'paytabs':

                    // Get payment gateway exchange rate
                    $gateway_currency_exchange = (float)settings('paytabs')->exchange_rate;

                    // Get total amount
                    $total_amount              = $this->calculateExchangeAmount($gateway_currency_exchange);

                    // Redirect
                    $pay = paypage::sendPaymentCode('all')
                            ->sendTransaction('sale')
                            ->sendCart( uid(42), $total_amount, __('messages.t_checkout') )
                            ->sendCustomerDetails(
                                auth()->user()->username, 
                                auth()->user()->email, 
                                'NA', 
                                'NA', 
                                'NA', 
                                'NA', 
                                'NA', 
                                'NA',
                                request()->ip()
                            )
                            ->sendHideShipping(true)
                            ->sendURLs(url('checkout/callback/paytabs'), url("checkout/callback/paytabs"))
                            ->sendLanguage('en')
                            ->create_pay_page();

                    // Reirect
                    return $pay;

                    break;

                // Razorpay
                case 'razorpay':
                    
                    // Get response
                    $response = $this->razorpay($options);

                    break;

                // Nowpayments
                case 'nowpayments':
                    
                    // Get response
                    $response = $this->nowpayments();

                    break;
                
                default:
                    
                    // Nothing selected
                    return;

                    break;
            }

            // Check if response succeeded
            if (isset($response['success']) && $response['success'] === true) {

                // Get user billing info
                $billing_info          = auth()->user()->billing;

                // Get commission settings
                $commission_settings   = settings('commission');

                // Set unique id for this order
                $uid                   = uid();

                // Get buyer id
                $buyer_id              = auth()->id();

                // Count taxes amount
                $taxes                 = $this->taxes();

                // Count subtotal amount
                $subtotal              = $this->subtotal();

                // Count total amount
                $total                 = $this->total() + $taxes;

                // Save order
                $order                 = new Order();
                $order->uid            = $uid;
                $order->buyer_id       = $buyer_id;
                $order->total_value    = $total;
                $order->subtotal_value = $subtotal;
                $order->taxes_value    = $taxes;
                $order->save();

                // Now let's loop through items in this cart and save them
                foreach ($this->cart as $key => $item) {
                    
                    // Get gig
                    $gig = Gig::where('uid', $item['id'])->active()->first();

                    // Check if gig exists
                    if ($gig) {
                        
                        // Get item total price
                        $item_total_price                   = $this->itemTotalPrice($item['id']);

                        // Calculate commission first
                        $commisssion                        = $commission_settings->commission_from === 'orders' ? $this->commission($item_total_price) : 0;

                        // Save order item
                        $order_item                         = new OrderItem();
                        $order_item->uid                    = uid();
                        $order_item->order_id               = $order->id;
                        $order_item->gig_id                 = $gig->id;
                        $order_item->owner_id               = $gig->user_id;
                        $order_item->quantity               = (int) $item['quantity'];
                        $order_item->has_upgrades           = is_array($item['upgrades']) && count($item['upgrades']) ? true : false;
                        $order_item->total_value            = $item_total_price;
                        $order_item->profit_value           = $item_total_price - $commisssion;
                        $order_item->commission_value       = $commisssion;
                        $order_item->save();

                        // Check if this item has upgrades
                        if ( is_array($item['upgrades']) && count($item['upgrades']) ) {
                            
                            // Loop through upgrades
                            foreach ($item['upgrades'] as $index => $upg) {
                                
                                // Get upgrade
                                $upgrade = GigUpgrade::where('uid', $upg['id'])->where('gig_id', $gig->id)->first();

                                // Check if upgrade exists
                                if ($upgrade) {
                                    
                                    // Save item upgrade
                                    $order_item_upgrade             = new OrderItemUpgrade();
                                    $order_item_upgrade->item_id    = $order_item->id;
                                    $order_item_upgrade->title      = $upgrade->title;
                                    $order_item_upgrade->price      = $upgrade->price;
                                    $order_item_upgrade->extra_days = $upgrade->extra_days;
                                    $order_item_upgrade->save();

                                }
                                
                            }

                        }

                        // Only if not offline payment
                        if ($this->payment_method !== 'offline') {
                            
                            // Update seller pending balance
                            $gig->owner()->update([
                                'balance_pending' => $gig->owner->balance_pending + $order_item->profit_value
                            ]);

                            // Increment orders in queue
                            $gig->increment('orders_in_queue');

                            // Order item placed successfully
                            // Let's notify the seller about new order
                            $gig->owner->notify( (new PendingOrder($order_item))->locale(config('app.locale')) );

                            // Send notification
                            notification([
                                'text'    => 't_u_received_new_order_seller',
                                'action'  => url('seller/orders/details', $order_item->uid),
                                'user_id' => $order_item->owner_id
                            ]);

                        }

                    }

                }

                // Save invoice
                $invoice                 = new OrderInvoice();
                $invoice->order_id       = $order->id;
                $invoice->payment_method = $response['transaction']['payment_method'];
                $invoice->payment_id     = $response['transaction']['payment_id'];
                $invoice->firstname      = $billing_info?->firstname ? $billing_info->firstname : auth()->user()->username;
                $invoice->lastname       = $billing_info?->lastname ? $billing_info->lastname : auth()->user()->username;
                $invoice->email          = auth()->user()->email;
                $invoice->company        = $billing_info->company ? clean($billing_info->company) : null;
                $invoice->address        = clean($billing_info->address);
                $invoice->status         = $response['transaction']['payment_status'];
                $invoice->save();

                // If invoice not paid yet
                if ($invoice->status === 'pending') {
                    
                    // Send notification to admin
                    Admin::first()->notify(new PendingOfflinePayment($order, $invoice));

                } else {

                    // Check if user paid from wallet
                    if ($this->payment_method !== 'wallet') {
                        
                        // Update balance
                        auth()->user()->update([
                            'balance_purchases' => $total
                        ]);

                    }

                }

                // Now everything succeeded
                // Let's empty the cart
                session()->forget('cart');

                // Now let's notify the buyer that his order has been placed
                auth()->user()->notify( (new OrderPlaced($order, $total))->locale(config('app.locale')) );

                // After that the buyer has to send the seller the required form to start
                if ($invoice->status === 'pending') {
                    
                    // Waiting for payment
                    return redirect('account/orders')->with('message', __('messages.t_order_placed_waiting_offline_payment'));

                } else {
                    
                    // Submit required files
                    return redirect('account/orders')->with('message', __('messages.t_u_have_send_reqs_asap_to_seller'));

                }

            } else {

                // Error
                $this->has_error     = true;
                $this->error_message = $response['message'];

                // Scroll up
                $this->dispatchBrowserEvent('scrollUp');

                // Return
                return;

            }
            
        } catch (\Illuminate\Validation\ValidationException $e) {

            // Validation error
            $this->notification([
                'title'       => __('messages.t_error'),
                'description' => __('messages.t_toast_form_validation_error'),
                'icon'        => 'error'
            ]);

            throw $e;

        } catch (\Throwable $th) {
            
            // Validation error
            $this->notification([
                'title'       => __('messages.t_error'),
                'description' => $th->getMessage(),
                'icon'        => 'error'
            ]);

        }
    }


    /**
     * Check if cart has items
     *
     * @return void
     */
    public function cartUpdated()
    {
        // Get current cart
        $cart = session('cart', []);

        // Check if cart has items
        if (count($cart) === 0) {
            return redirect('cart');
        }
    }


    /**
     * Validate cart
     *
     * @return void
     */
    protected function validateCart()
    {
        // Get items in cart
        $items = session('cart', []);

        // Check if cart has items
        if (is_array($items) && count($items)) {
            
            // Loop through items
            foreach ($items as $key => $item) {
                
                // Get gig id
                $id      = $item['id'];

                // Get current user id
                $user_id = auth()->id();

                // Get gig
                $gig     = Gig::where('uid', $id)->active()->where('user_id', '!=', $user_id)->first();

                // Check if gig does not exists
                if (!$gig) {
                    
                    // Remove this item from cart
                    unset($items[$key]);

                }

            }

            // Refresh items
            array_values($items);

        }

        // Forget old session
        session()->forget('cart');

        // Set new cart
        session()->put('cart', $items);
    }


    /**
     * Initialize Stripe payment gateway
     *
     * @return void
     */
    private function initStripe()
    {
        // Set your secret key. Remember to switch to your live secret key in production.
        $stripe = new \Stripe\StripeClient(config('stripe.secret_key'));

        $intent = $stripe->paymentIntents->create(
            [
                'amount'                    => $this->calculateExchangeAmount(settings('stripe')->exchange_rate) * 100,
                'currency'                  => settings('stripe')->currency,
                'automatic_payment_methods' => ['enabled' => true],
            ]
        );

        $this->stripe_intent_secret = $intent->client_secret;
    }

    
    /**
     * Init razorpay
     *
     * @return void
     */
    protected function initRazorpay()
    {
        // Generate order id
        $razorpay_api              = new Api(config('razorpay.key_id'), config('razorpay.key_secret'));

        // Get payment gateway exchange rate
        $gateway_currency_exchange = (float)settings('razorpay')->exchange_rate;

        // Get total amount
        $total_amount              = $this->calculateExchangeAmount($gateway_currency_exchange);

        $razorpay_order = $razorpay_api->order->create([
            'amount'   => $total_amount * 100,
            'currency' => settings('razorpay')->currency,
        ]);

        // Set order id
        $this->razorpay_order_id = $razorpay_order->id;
    }


    /**
     * Handle paypal payment
     *
     * @param string $order_id
     * @return array
     */
    protected function paypal($order_id)
    {
        try {

            // Get default currency exchange rate
            $default_currency_exchange = (float)settings('currency')->exchange_rate;

            // Get payment gateway exchange rate
            $gateway_currency_exchange = (float)settings('paypal')->exchange_rate;

            // Get total amount
            $total_amount              = $this->calculateExchangeAmount($gateway_currency_exchange);

            // Set paypal provider and config
            $client                    = new PayPalClient();
    
            // Get paypal access token
            $client->getAccessToken();

            // Capture this order
            $order                     = $client->capturePaymentOrder($order_id);

            // Let's see if payment suuceeded
            if ( is_array($order) && isset($order['status']) && $order['status'] === 'COMPLETED' ) {
                
                // Get paid amount
                $amount   = $order['purchase_units'][0]['payments']['captures'][0]['amount']['value'];

                // Get currency
                $currency = $order['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'];

                // Check currency
                if (strtolower($currency) != strtolower(config('paypal.currency'))) {
                    
                    // Error
                    $response = [
                        'success'  => false,
                        'message'  => __('messages.t_checkout_currency_invalid')
                    ];

                    return $response;

                }

                // This amount must equals amount in order
                if ($amount != $total_amount) {
                    
                    // Error
                    $response = [
                        'success'  => false,
                        'message'  => __('messages.t_amount_in_cart_not_equals_received')
                    ];

                    return $response;

                }

                // Payment succeeded
                $response = [
                    'success'     => true,
                    'transaction' => [
                        'payment_id'     => $order['id'],
                        'payment_method' => 'paypal',
                        'payment_status' => 'paid'
                    ]
                ];

                // Return response
                return $response;

            } else {

                // We couldn't handle your payment
                $response = [
                    'success'  => false,
                    'message'  => __('messages.t_we_could_not_handle_ur_payment')
                ];

                // Return response
                return $response;

            }

        } catch (\Throwable $th) {
            
            // Something went wrong
            $response = [
                'success'  => false,
                'message'  => $th->getMessage()
            ];

            // Return response
            return $response;

        }
    }


    /**
     * Handle wallet payment
     *
     * @return array
     */
    protected function wallet()
    {
        try {

            // Get total amount
            $total_amount = $this->calculateExchangeAmount();

            // Check if user has enough money
            if (auth()->user()->balance_available < $total_amount) {
                    
                // You don't have enough money
                $response = [
                    'success'  => false,
                    'message'  => __('messages.t_u_dont_have_enough_money_to_checkout')
                ];

                return $response;

            } else {

                // Let's take money from buyer's wallet
                auth()->user()->update([
                    'balance_purchases' => $total_amount,
                    'balance_available' => auth()->user()->balance_available - $total_amount
                ]);

                // Payment succeeded
                $response = [
                    'success'     => true,
                    'transaction' => [
                        'payment_id'     => uid(),
                        'payment_method' => 'wallet',
                        'payment_status' => 'paid'
                    ]
                ];

                // Return response
                return $response;


            }

        } catch (\Throwable $th) {
            
            // Something went wrong
            $response = [
                'success'  => false,
                'message'  => $th->getMessage()
            ];

            // Return response
            return $response;

        }
    }


    /**
     * Handle paystack payment
     *
     * @param string $reference_id
     * @return array
     */
    protected function paystack($reference_id)
    {
        try {

            // Get payment gateway exchange rate
            $gateway_currency_exchange = (float)settings('paystack')->exchange_rate;

            // Get total amount
            $total_amount              = $this->calculateExchangeAmount($gateway_currency_exchange);

            // Get paystack secret key
            $paystack_secret_key       = config('paystack.secretKey');

            // Send request
            $client                    = Http::withHeaders([
                'Authorization' => 'Bearer ' . $paystack_secret_key,
                'Accept'        => 'application/json',
            ])->get("https://api.paystack.co/transaction/verify/$reference_id");

            // Convert to json
            $payment                    = $client->json();

            // Let's see if payment suuceeded
            if ( is_array($payment) && isset($payment['status']) && $payment['status'] === true && isset($payment['data']) ) {
                
                // Get paid amount
                $amount   = $payment['data']['amount'] / 100;

                // Get currency
                $currency = $payment['data']['currency'];

                // Check currency
                if (strtolower($currency) != strtolower(settings('paystack')->currency)) {
                    
                    // Error
                    $response = [
                        'success'  => false,
                        'message'  => __('messages.t_checkout_currency_invalid')
                    ];

                    return $response;

                }

                // This amount must equals amount in order
                if ($amount != $total_amount) {
                    
                    // Error
                    $response = [
                        'success'  => false,
                        'message'  => __('messages.t_amount_in_cart_not_equals_received')
                    ];

                    return $response;

                }

                // Payment succeeded
                $response = [
                    'success'     => true,
                    'transaction' => [
                        'payment_id'     => $payment['data']['id'],
                        'payment_method' => 'paystack',
                        'payment_status' => 'paid'
                    ]
                ];

                // Return response
                return $response;

            } else {

                // We couldn't handle your payment
                $response = [
                    'success'  => false,
                    'message'  => __('messages.t_we_could_not_handle_ur_payment')
                ];

                // Return response
                return $response;

            }

        } catch (\Throwable $th) {
            
            // Something went wrong
            $response = [
                'success'  => false,
                'message'  => $th->getMessage()
            ];

            // Return response
            return $response;

        }
    }


    /**
     * Handle cashfree payment
     *
     * @param string $order_id
     * @return array
     */
    protected function cashfree($order_id)
    {
        try {

            // Get payment gateway exchange rate
            $gateway_currency_exchange = (float)settings('cashfree')->exchange_rate;

            // Get total amount
            $total_amount              = $this->calculateExchangeAmount($gateway_currency_exchange);

            // Set api url
            $api_url                   = config('cashfree.isLive') ? "https://api.cashfree.com/pg/orders/$order_id" : "https://sandbox.cashfree.com/pg/orders/$order_id";

            // Set client id
            $client_id                 = config('cashfree.appID');

            // Set client secret
            $client_secret             = config('cashfree.secretKey');
            
            // Init curl request
            $curl                      = curl_init();

            // Set config
            curl_setopt_array($curl, [
                CURLOPT_URL            => $api_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING       => "",
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST  => "GET",
                CURLOPT_HTTPHEADER     => [
                    "Accept: application/json",
                    "Content-Type: application/json",
                    "x-api-version: 2022-01-01",
                    "x-client-id: $client_id",
                    "x-client-secret: $client_secret"
                ],
            ]);

            // Get response
            $response = curl_exec($curl);

            // Get error
            $error    = curl_error($curl);

            // Close connection
            curl_close($curl);
            
            // Check if request has error
            if ($error) {
            
                // Error
                $response = [
                    'success'  => false,
                    'message'  => $error
                ];

                // Return response
                return $response;

            } else {

                // Decode results
                $payment                     = json_decode($response, true);

                // Let's see if payment suuceeded
                if ( is_array($payment) && isset($payment['order_status']) && $payment['order_status'] === 'PAID' ) {

                    // Get paid amount
                    $amount   = $payment['order_amount'];

                    // Get currency
                    $currency = $payment['order_currency'];

                    // Check currency
                    if (strtolower($currency) != strtolower(settings('cashfree')->currency)) {
                        
                        // Error
                        $response = [
                            'success'  => false,
                            'message'  => __('messages.t_checkout_currency_invalid')
                        ];

                        return $response;

                    }

                    // This amount must equals amount in order
                    if ($amount != $total_amount) {
                        
                        // Error
                        $response = [
                            'success'  => false,
                            'message'  => __('messages.t_amount_in_cart_not_equals_received')
                        ];

                        return $response;

                    }

                    // Payment succeeded
                    $response = [
                        'success'     => true,
                        'transaction' => [
                            'payment_id'     => $payment['cf_order_id'],
                            'payment_method' => 'cashfree',
                            'payment_status' => 'paid'
                        ]
                    ];

                    // Return response
                    return $response;

                } else {

                    // We couldn't handle your payment
                    $response = [
                        'success'  => false,
                        'message'  => __('messages.t_we_could_not_handle_ur_payment')
                    ];

                    // Return response
                    return $response;

                }

            }

        } catch (\Throwable $th) {
            
            // Something went wrong
            $response = [
                'success'  => false,
                'message'  => $th->getMessage()
            ];

            // Return response
            return $response;

        }
    }


    /**
     * Handle xendit payment
     *
     * @param array $data
     * @return array
     */
    protected function xendit($data)
    {
        try {

            // Get payment gateway exchange rate
            $gateway_currency_exchange = (float) settings('xendit')->exchange_rate;

            // Get total amount
            $total_amount              = ceil($this->calculateExchangeAmount($gateway_currency_exchange));

            // Set api secret key
            Xendit::setApiKey(config('xendit.secret_key'));

            $xendit_params = [
                'token_id'          => $data['token'],
                'external_id'       => uid(32),
                'authentication_id' => $data['authentication_id'],
                'amount'            => $total_amount,
                'card_cvn'          => $data['cvn'],
                'capture'           => false
            ];
            
            // Get payment
            $payment = \Xendit\Cards::create($xendit_params);

            // Let's see if payment suuceeded
            if ( is_array($payment) && isset($payment['status']) && ($payment['status'] === 'AUTHORIZED' || $payment['status'] === 'CAPTURED') ) {
                
                // Get paid amount
                $amount   = $payment['authorized_amount'];

                // Get currency
                $currency = $payment['currency'];

                // Check currency
                if (strtolower($currency) != strtolower(settings('xendit')->currency)) {
                    
                    // Error
                    $response = [
                        'success'  => false,
                        'message'  => __('messages.t_checkout_currency_invalid')
                    ];

                    return $response;

                }

                // This amount must equals amount in order
                if ($amount != $total_amount) {
                    
                    // Error
                    $response = [
                        'success'  => false,
                        'message'  => __('messages.t_amount_in_cart_not_equals_received')
                    ];

                    return $response;

                }

                // Payment succeeded
                $response = [
                    'success'     => true,
                    'transaction' => [
                        'payment_id'     => $payment['id'],
                        'payment_method' => 'xendit',
                        'payment_status' => 'paid'
                    ]
                ];

                // Return response
                return $response;

            } else {

                // We couldn't handle your payment
                $response = [
                    'success'  => false,
                    'message'  => __('messages.t_we_could_not_handle_ur_payment')
                ];

                // Return response
                return $response;

            }

        } catch (\Throwable $th) {
            
            // Something went wrong
            $response = [
                'success'  => false,
                'message'  => $th->getMessage()
            ];

            // Return response
            return $response;

        }
    }


    /**
     * Handle mercadopago payment
     *
     * @param array $data
     * @return array
     */
    protected function mercadopago($data)
    {
        try {

            // Get payment gateway exchange rate
            $gateway_currency_exchange = (float) settings('mercadopago')->exchange_rate;

            // Get total amount
            $total_amount              = $this->calculateExchangeAmount($gateway_currency_exchange);

            // Set api secret key
            \MercadoPago\SDK::setAccessToken(config('mercadopago.access_token'));

            // Create new chanrge
            $payment                     = new \MercadoPago\Payment();
            $payment->transaction_amount = $total_amount;
            $payment->token              = $data['token_id'];
            $payment->description        = __('messages.t_checkout');
            $payment->installments       = $data['installments'];
            $payment->payment_method_id  = $data['payment_method_id'];
            $payment->payer              = ["email" => $data['payer_email']];
            $payment->save();

            // Let's see if payment suuceeded
            if ( $payment && $payment->status === 'approved' ) {

                // Get paid amount
                $amount   = $payment->transaction_amount;

                // Get currency
                $currency = $payment->currency_id;

                // Check currency
                if (strtolower($currency) != strtolower(settings('mercadopago')->currency)) {
                    
                    // Error
                    $response = [
                        'success'  => false,
                        'message'  => __('messages.t_checkout_currency_invalid')
                    ];

                    return $response;

                }

                // This amount must equals amount in order
                if ($amount != $total_amount) {
                    
                    // Error
                    $response = [
                        'success'  => false,
                        'message'  => __('messages.t_amount_in_cart_not_equals_received')
                    ];

                    return $response;

                }

                // Payment succeeded
                $response = [
                    'success'     => true,
                    'transaction' => [
                        'payment_id'     => $payment->id,
                        'payment_method' => 'mercadopago',
                        'payment_status' => 'paid'
                    ]
                ];

                // Return response
                return $response;

            } else {

                // We couldn't handle your payment
                $response = [
                    'success'  => false,
                    'message'  => __('messages.t_we_could_not_handle_ur_payment')
                ];

                // Return response
                return $response;

            }

        } catch (\Throwable $th) {
            
            // Something went wrong
            $response = [
                'success'  => false,
                'message'  => $th->getMessage()
            ];

            // Return response
            return $response;

        }
    }


    /**
     * Handle razorpay payment
     *
     * @param array $data
     * @return array
     */
    protected function razorpay($data)
    {
        try {

            // Get payment gateway exchange rate
            $gateway_currency_exchange = (float) settings('razorpay')->exchange_rate;

            // Get total amount
            $total_amount              = $this->calculateExchangeAmount($gateway_currency_exchange);

            // Get payment id
            $razorpay_payment_id       = $data['razorpay_payment_id'];

            // Get order id
            $razorpay_order_id         = $data['razorpay_order_id'];

            // Get signature
            $razorpay_signature        = $data['razorpay_signature'];

            // Set api
            $api                       = new Api(config('razorpay.key_id'), config('razorpay.key_secret'));

            // Let's verify first the signature
            $api->utility->verifyPaymentSignature([
                'razorpay_signature'  => $razorpay_signature,
                'razorpay_payment_id' => $razorpay_payment_id,
                'razorpay_order_id'   => $razorpay_order_id
            ]);

            // Fetch this payment
            $fetch_payment             = $api->payment->fetch($razorpay_payment_id);

            // Check if payment authorized
            if ($fetch_payment->status === 'authorized') {
                
                // Let capture this payment
                $payment = $api->payment->fetch($razorpay_payment_id)->capture([
                    'amount'   => $total_amount * 100,
                    'currency' => settings('razorpay')->currency
                ]);

            } else if ($fetch_payment->status === 'captured') {
                
                // Set payment
                $payment = $fetch_payment;

            } else {

                // Payment failed
                $response = [
                    'success'  => false,
                    'message'  => __('messages.t_we_could_not_handle_ur_payment')
                ];

                // Return response
                return $response;

            }

            // Let's see if payment suuceeded
            if ( $payment && $payment->status === 'captured' ) {

                // Get paid amount
                $amount   = $payment->amount / 100;

                // Get currency
                $currency = $payment->currency;

                // Check currency
                if (strtolower($currency) != strtolower(settings('razorpay')->currency)) {
                    
                    // Error
                    $response = [
                        'success'  => false,
                        'message'  => __('messages.t_checkout_currency_invalid')
                    ];

                    return $response;

                }

                // This amount must equals amount in order
                if ($amount != $total_amount) {
                    
                    // Error
                    $response = [
                        'success'  => false,
                        'message'  => __('messages.t_amount_in_cart_not_equals_received')
                    ];

                    return $response;

                }

                // Payment succeeded
                $response = [
                    'success'     => true,
                    'transaction' => [
                        'payment_id'     => $payment->id,
                        'payment_method' => 'razorpay',
                        'payment_status' => 'paid'
                    ]
                ];

                // Return response
                return $response;

            } else {

                // We couldn't handle your payment
                $response = [
                    'success'  => false,
                    'message'  => __('messages.t_we_could_not_handle_ur_payment')
                ];

                // Return response
                return $response;

            }

        } catch (\Throwable $th) {
            
            // Something went wrong
            $response = [
                'success'  => false,
                'message'  => $th->getMessage()
            ];

            // Return response
            return $response;

        }
    }


    /**
     * Handle nowpayments payment
     *
     * @return array
     */
    protected function nowpayments()
    {
        try {

            // Get payment gateway exchange rate
            $gateway_currency_exchange = (float) settings('nowpayments')->exchange_rate;

            // Get total amount
            $total_amount              = $this->calculateExchangeAmount($gateway_currency_exchange);

            $client  = new Client();
            $headers = [
                'x-api-key'    => config('nowpayments.api_key'),
                'Content-Type' => 'application/json'
            ];
            $request = new Request('GET', config('nowpayments.payment_url') . '/' . $this->nowpayments_payment_id, $headers);
            $res     = $client->sendAsync($request)->wait();
            $data    = json_decode($res->getBody(), true);

            // Let's see if payment suuceeded
            if ( is_array($data) && isset($data['payment_status']) && $data['payment_status'] == 'finished' ) {

                // Get paid amount
                $amount          = $data['price_amount'];

                // Get currency
                $currency        = $data['price_currency'];

                // Get crypto currency
                $crypto_currency = $data['pay_currency'];

                // Check currency
                if (strtolower($currency) != strtolower(settings('nowpayments')->currency) || strtolower($crypto_currency) != settings('nowpayments')->crypto_currency) {
                    
                    // Error
                    $response = [
                        'success'  => false,
                        'message'  => __('messages.t_checkout_currency_invalid')
                    ];

                    return $response;

                }

                // This amount must equals amount in order
                if ($amount != $total_amount) {
                    
                    // Error
                    $response = [
                        'success'  => false,
                        'message'  => __('messages.t_amount_in_cart_not_equals_received')
                    ];

                    return $response;

                }

                // Payment succeeded
                $response = [
                    'success'     => true,
                    'transaction' => [
                        'payment_id'     => $data['payment_id'],
                        'payment_method' => 'nowpayments',
                        'payment_status' => 'paid'
                    ]
                ];

                // Return response
                return $response;

            } else {

                // We couldn't handle your payment
                $response = [
                    'success'  => false,
                    'message'  => __('messages.t_we_could_not_handle_ur_payment')
                ];

                // Return response
                return $response;

            }

        } catch (\Throwable $th) {
            
            // Something went wrong
            $response = [
                'success'  => false,
                'message'  => $th->getMessage()
            ];

            // Return response
            return $response;

        }
    }


    /**
     * Calculate exchange rate
     *
     * @param float $amount
     * @param float $gateway_exchange_rate
     * @return mixed
     */
    protected function calculateExchangeAmount($gateway_exchange_rate = null)
    {
        try {
            
            // Get total amount
            $amount                = $this->total() + $this->taxes();

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
     * Get paymob payment token
     *
     * @return array
     */
    public function getPayMobPaymentKey()
    {
        try {
            
            // Validate form
            if (!$this->paymob_firstname || !$this->paymob_lastname || !$this->paymob_phone) {
                
                // Error
                $this->notification([
                    'title'       => __('messages.t_error'),
                    'description' => __('messages.t_toast_form_validation_error'),
                    'icon'        => 'error'
                ]);

                return;

            }

            // Get payment gateway exchange rate
            $gateway_currency_exchange = (float) settings('paymob')->exchange_rate;

            // Get total amount
            $total_amount              = $this->calculateExchangeAmount($gateway_currency_exchange);

            // Get auth token
            $auth    = Http::acceptJson()->post('https://accept.paymob.com/api/auth/tokens', [
                                'api_key' => config('paymob.api_key'),
                            ])->json();
        
            // Create order
            $order   = Http::acceptJson()->post('https://accept.paymob.com/api/ecommerce/orders', [
                                'auth_token'      => $auth['token'],
                                'delivery_needed' => false,
                                'amount_cents'    => $total_amount * 100,
                                'items'           => []
                            ])->json();
        
            // Make payment
            $payment = Http::acceptJson()->post('https://accept.paymob.com/api/acceptance/payment_keys', [
                                'auth_token'     => $auth['token'],
                                'amount_cents'   => $total_amount * 100,
                                'expiration'     => 3600,
                                'order_id'       => $order['id'],
                                'billing_data'   => [
                                    "first_name"     => $this->paymob_firstname,
                                    "last_name"      => $this->paymob_lastname,
                                    "email"          => auth()->user()->email,
                                    "phone_number"   => $this->paymob_phone,
                                    "apartment"      => "NA",
                                    "floor"          => "NA",
                                    "street"         => "NA",
                                    "building"       => "NA",
                                    "shipping_method"=> "NA",
                                    "postal_code"    => "NA",
                                    "city"           => "NA",
                                    "country"        => "NA",
                                    "state"          => "NA"
                                ],
                                'currency'       => settings('paymob')->currency,
                                'integration_id' => config('paymob.integration_id')
                            ])->json();
        
            // Set session
            session()->put('paymob_callback', 'checkout');

            // Set payment token
            $this->paymob_payment_token = $payment['token'];

        } catch (\Throwable $th) {
            
            // Error
            $this->notification([
                'title'       => __('messages.t_error'),
                'description' => __('messages.t_toast_something_went_wrong'),
                'icon'        => 'error'
            ]);

        }
    }


    /**
     * Save checkout for webhook callback
     *
     * @param array $data
     * @return void
     */
    protected function checkoutWebhook($data)
    {
        try {
            
            // Set buyer id
            $buyer_id                = auth()->id();
            
            // Set cart
            $cart                    = $this->cart;

            // Set payment id
            $payment_id              = $data['payment_id'];

            // Set payment method 
            $payment_method          = $data['payment_method'];

            // Save
            $webhook                 = new CheckoutWebhook();
            $webhook->data           = ['buyer_id' => $buyer_id, 'cart' => $cart];
            $webhook->payment_id     = $payment_id;
            $webhook->payment_method = $payment_method;
            $webhook->save();

        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * Generate YouCanPay payment link
     *
     * @return string
     */
    private function generateYoucanpayUrl()
    {
        // Get youcanpay settings
        $settings = settings('youcanpay');

        // Enable sandbox mode?
        if (Str::of(config('youcanpay.public_key'))->startsWith('pub_sandbox')) {
            YouCanPay::setIsSandboxMode(true);
        }

        // Get payment gateway exchange rate
        $gateway_currency_exchange = $settings->exchange_rate;

        // Get total amount
        $total_amount              = $this->calculateExchangeAmount($gateway_currency_exchange);

        // Create a YouCan Pay instance, to retrieve your private and public keys login to your YouCan Pay account
        // and go to Settings and open API Keys.
        $youCanPay = YouCanPay::instance()->useKeys(config('youcanpay.private_key'), config('youcanpay.public_key'));

        // Data of the customer who wishes to make this purchase.
        // Please keep these keys.
        $customerInfo = [
            'name'         => '',
            'address'      => '',
            'zip_code'     => '',
            'city'         => '',
            'state'        => '',
            'country_code' => '',
            'phone'        => '',
            'email'        => '',
        ];

        // You can use it to send data to retrieve after the response or in the webhook.
        $metadata = [
            // Can you insert what you want here...
            //'key' => 'value'
        ];

        // Create the order you want to be paid
        $token = $youCanPay->token->create(
            // String orderId (required): Identifier of the order you want to be paid.
            uid(),
            // Integer amount (required): The amount, Example: 25 USD is 2500.
            $total_amount * 100,
            // String currency (required): Uppercase currency.
            $settings->currency,
            // String customerIP (required): Customer Address IP.
            request()->ip(),
            // String successUrl (required): This URL is returned when the payment is successfully processed.
            url('checkout/callback/youcanpay'),
            // String errorUrl (required): This URL is returned when payment is invalid.
            url('checkout/callback/youcanpay'),
            // Array customerInfo (optional): Data of the customer who wishes to make this purchase.
            $customerInfo,
            // Array metadata (optional): You can use it to send data to retrieve after the response or in the webhook.
            $metadata
        );

        // Redirect to payment gateway
        return $token->getPaymentURL(app()->getLocale());
    }
    
}
