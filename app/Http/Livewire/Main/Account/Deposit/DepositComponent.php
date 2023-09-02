<?php

namespace App\Http\Livewire\Main\Account\Deposit;

use DateTime;
use DateTimeZone;
use Xendit\Xendit;
use App\Models\User;
use Razorpay\Api\Api;
use Livewire\Component;
use YouCan\Pay\YouCanPay;
use WireUi\Traits\Actions;
use Illuminate\Support\Str;
use App\Models\DepositWebhook;
use App\Models\DepositTransaction;
use Illuminate\Support\Facades\Http;
use Paytabscom\Laravel_paytabs\Facades\paypage;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class DepositComponent extends Component
{

    use SEOToolsTrait, Actions;

    public $selected      = null;
    public $fee           = 0;
    public $amount        = null;
    public $is_third_step = false;
    public $isSucceeded   = false;
    public $stripe_intent_secret;

    // Paymob
    public $paymob_phone = "+";
    public $paymob_firstname;
    public $paymob_lastname;
    public $paymob_payment_token;

    // Razorpay
    public $razorpay_order_id;

    // JazzCash
    public $jazzcash_request;

    // NowPayments.io
    public $nowpayments_pay_address;
    public $nowpayments_payment_id;
    public $nowpayments_payment_status;
    public $nowpayments_price_amount;
    public $nowpayments_pay_amount;

    /**
     * Render component
     *
     * @return Illuminate\View\View
     */
    public function render()
    {
        // SEO
        $separator   = settings('general')->separator;
        $title       = __('messages.t_deposit') . " $separator " . settings('general')->title;
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

        return view('livewire.main.account.deposit.deposit')->extends('livewire.main.layout.app')->section('content');
    }


    /**
     * Listent when amount is changed
     *
     * @param mixed $value
     * @return void
     */
    public function updatedAmount($value)
    {
        try {
            
            // Must be a valid number
            if (is_numeric($value) && $value > 0) {
                
                // Calculate fee
                $this->calculateFee();

            } else {

                // Invalid number
                $this->amount = null;

            }

        } catch (\Throwable $th) {
            
            // Something went wrong
            $this->amount = null;

        }
    }
    

    /**
     * Calculate fee
     *
     * @param mixed $amount
     * @return mixed
     */
    protected function calculateFee($amount = null)
    {
        try {
            
            // Check selected payment gateway
            switch ($this->selected) {

                // PayPal
                case 'paypal':
                    $fee_rate = settings('paypal')->deposit_fee;
                    break;

                // Cashfree
                case 'cashfree':
                    $fee_rate = settings('cashfree')->deposit_fee;
                    break;

                // Flutterwave
                case 'flutterwave':
                    $fee_rate = settings('flutterwave')->deposit_fee;
                    break;

                // Mercadopago
                case 'mercadopago':
                    $fee_rate = settings('mercadopago')->deposit_fee;
                    break;

                // Mollie
                case 'mollie':
                    $fee_rate = settings('mollie')->deposit_fee;
                    break;

                // Offline payment
                case 'offline_payment':
                    $fee_rate = settings('offline_payment')->deposit_fee;
                    break;

                // Paymob
                case 'paymob':
                    $fee_rate = settings('paymob')->deposit_fee;
                    break;

                // Paystack
                case 'paystack':
                    $fee_rate = settings('paystack')->deposit_fee;
                    break;

                // Paytabs
                case 'paytabs':
                    $fee_rate = settings('paytabs')->deposit_fee;
                    break;

                // Paytr
                case 'paytr':
                    $fee_rate = settings('paytr')->deposit_fee;
                    break;

                // Razorpay
                case 'razorpay':
                    $fee_rate = settings('razorpay')->deposit_fee;
                    break;

                // Stripe
                case 'stripe':
                    $fee_rate = settings('stripe')->deposit_fee;
                    break;

                // Vnpay
                case 'vnpay':
                    $fee_rate = settings('vnpay')->deposit_fee;
                    break;

                // Xendit
                case 'xendit':
                    $fee_rate = settings('xendit')->deposit_fee;
                    break;

                // Jazzcash
                case 'jazzcash':
                    $fee_rate = settings('jazzcash')->deposit_fee;
                    break;

                // Youcanpay
                case 'youcanpay':
                    $fee_rate = settings('youcanpay')->deposit_fee;
                    break;

                // Nowpayments
                case 'nowpayments':
                    $fee_rate = settings('nowpayments')->deposit_fee;
                    break;
                
                default:
                    $fee_rate = 0;
                    break;
            }

            // Check if a specified amount is set
            if ($amount) {
                
                // Calculate fee
                return $amount * $fee_rate / 100;

            } else {

                // Calculate fee
                $this->fee = $this->amount * $fee_rate / 100;

            }

        } catch (\Throwable $th) {
            
            // Something went wrong
            $this->fee = 0;

        }
    }


    /**
     * Go to next step
     *
     * @return void
     */
    public function next()
    {
        try {
            
            // Check if amount is correct
            if (is_numeric($this->amount) && $this->amount >= 1) {
                
                // Check selected payment method
                switch ($this->selected) {

                    // PayPal
                    case 'paypal':
                        
                        // Go to next step
                        $this->is_third_step = true;

                        break;
    
                    // Cashfree
                    case 'cashfree':

                        // Go to next step
                        $this->is_third_step = true;

                        break;
    
                    // Flutterwave
                    case 'flutterwave':

                        // Go to next step
                        $this->is_third_step = true;

                        break;
    
                    // Mercadopago
                    case 'mercadopago':
                        
                        // Go to next step
                        $this->is_third_step = true;
                        
                        break;
    
                    // Mollie
                    case 'mollie':

                        // Handle mollie payment
                        $payment = $this->mollie();

                        // Check if payment succeeded
                        if (is_array($payment)) {
                            
                            // Error
                            $this->notification([
                                'title'       => __('messages.t_error'),
                                'description' => $payment['message'],
                                'icon'        => 'error'
                            ]);

                        }

                        break;
    
                    // Offline payment
                    case 'offline_payment':

                        // Go to next step
                        $this->is_third_step = true;

                        break;
    
                    // Paymob
                    case 'paymob':
                        
                        // Check form is valid
                        if (!$this->paymob_firstname || !$this->paymob_firstname || !$this->paymob_lastname) {
                            
                            // Error
                            $this->notification([
                                'title'       => __('messages.t_error'),
                                'description' => __('messages.t_pls_check_ur_inputs_and_try_again'),
                                'icon'        => 'error'
                            ]);

                            // Return 
                            return;

                        }

                        // Get paymob payment key
                        $paymob = $this->getPayMobPaymentKey();

                        // Check if request succeeded
                        if ($paymob['success']) {

                            // Set session key
                            session()->put('paymob_callback', 'deposit');
                            
                            // Go to next step
                            $this->is_third_step = true;

                        } else {

                            // Something went wrong
                            $this->notification([
                                'title'       => __('messages.t_error'),
                                'description' => $paymob['message'],
                                'icon'        => 'error'
                            ]);

                        }

                        break;
    
                    // Paystack
                    case 'paystack':

                        // Go to next step
                        $this->is_third_step = true;

                        break;
    
                    // Paytabs
                    case 'paytabs':
                        
                        // Get default currency exchange rate
                        $default_currency_exchange = settings('currency')->exchange_rate;

                        // Get payment gateway exchange rate
                        $gateway_currency_exchange = settings('paytabs')->exchange_rate;

                        // Get paytabs currency
                        $gateway_currency          = config('paytabs.currency');

                        // Set provider name
                        $provider_name             = 'paytabs';

                        // Caluclate fee
                        $fee                       = $this->calculateFee($this->amount);

                        // Generate transaction id
                        $transaction_id            = Str::uuid();

                        // Make transaction
                        $deposit                 = new DepositTransaction();
                        $deposit->user_id        = auth()->id();
                        $deposit->transaction_id = $transaction_id;
                        $deposit->payment_method = $provider_name;
                        $deposit->amount_total   = round( ($this->amount * $default_currency_exchange) / $gateway_currency_exchange, 2 );
                        $deposit->amount_fee     = round( ($fee * $default_currency_exchange) / $gateway_currency_exchange, 2 );
                        $deposit->amount_net     = round( ( ($this->amount - $fee ) * $default_currency_exchange ) / $gateway_currency_exchange, 2 );
                        $deposit->currency       = $gateway_currency;
                        $deposit->exchange_rate  = $gateway_currency_exchange;
                        $deposit->status         = 'pending';
                        $deposit->ip_address     = request()->ip();
                        $deposit->save();
                        
                        // Redirect
                        $pay= paypage::sendPaymentCode('all')
                                    ->sendTransaction('sale')
                                    ->sendCart( $transaction_id, $this->amount, __('messages.t_add_funds') )
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
                                    ->sendURLs(url("account/deposit/callback/paytabs?t=$transaction_id"), url("callback/paytabs/deposit?t=$transaction_id"))
                                    ->sendLanguage('en')
                                    ->create_pay_page();

                        // Reirect
                        return $pay;

                        break;
    
                    // Paytr
                    case 'paytr':

                        // Go to next step
                        $this->is_third_step = true;
                        
                        break;
    
                    // Razorpay
                    case 'razorpay':

                        // Generate order id
                        $razorpay_api   = new Api(config('razorpay.key_id'), config('razorpay.key_secret'));

                        $razorpay_order = $razorpay_api->order->create([
                            'amount'   => $this->amount * 100,
                            'currency' => settings('razorpay')->currency,
                        ]);

                        // Set order id
                        $this->razorpay_order_id = $razorpay_order->id;

                        // Go to next step
                        $this->is_third_step = true;

                        break;
    
                    // Stripe
                    case 'stripe':
                        
                        // Generate stripe intent key
                        $this->getStripeIntent();

                        // Go to next step
                        $this->is_third_step = true;

                        break;
    
                    // Vnpay
                    case 'vnpay':

                        // Go to next step
                        $this->is_third_step = true;

                        break;
    
                    // Xendit
                    case 'xendit':
                        
                        // Amount must be integer
                        if (!filter_var($this->amount, FILTER_VALIDATE_INT)) {
                            
                            // You have to select a correct amount
                            $this->notification([
                                'title'       => __('messages.t_error'),
                                'description' => __('messages.t_amount_must_be_integer'),
                                'icon'        => 'error'
                            ]);

                        } else {

                            // Go to next step
                            $this->is_third_step = true;

                        }

                        break;

                    // JazzCash
                    case 'jazzcash':

                        // Go to next step
                        $this->is_third_step = true;

                        break;

                    // Youcanpay
                    case 'youcanpay':

                        // Redirect to payment url
                        return redirect($this->generateYoucanpayUrl());

                        break;

                    // Nowpayments
                    case 'nowpayments':

                        try {
                
                            $client  = new Client();
                            $headers = [
                                'x-api-key'    => config('nowpayments.api_key'),
                                'Content-Type' => 'application/json'
                            ];
            
                            $body = [
                                "price_amount"      => $this->amount,
                                "price_currency"    => settings('nowpayments')->currency,
                                "pay_currency"      => settings('nowpayments')->crypto_currency,
                                "ipn_callback_url"  => "https://nowpayments.io",
                                "order_id"          => uid(),
                                "order_description" => __('messages.t_add_funds')
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

                                // Go to next step
                                $this->is_third_step = true;
            
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

                        break;
                    
                    default:
                        $fee_rate = 0;
                        break;
                }

            } else {

                // You have to select a correct amount
                $this->notification([
                    'title'       => __('messages.t_error'),
                    'description' => __('messages.t_deposit_amount_incorrect'),
                    'icon'        => 'error'
                ]);

                return;

            }

        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * Back to previews step
     *
     * @return void
     */
    public function back()
    {
        $this->selected = null;
    }


    /**
     * Handle payment
     *
     * @param mixed $data
     * @return void
     */
    public function handle($data = null)
    {
        try {
            
            // Check selected payment method
            switch ($this->selected) {

                // PayPal
                case 'paypal':
                    
                    // Handle paypal payment
                    $response = $this->paypal($data);

                    break;

                // Cashfree
                case 'cashfree':
                    $fee_rate = settings('cashfree')->deposit_fee;
                    break;

                // Flutterwave
                case 'flutterwave':
                    
                    // Handle flutterwave payment
                    $response = $this->flutterwave();

                    break;

                // Mercadopago
                case 'mercadopago':
                    
                    // Handle mercadopago payment
                    $response = $this->mercadopago($data);

                    break;

                // Mollie
                case 'mollie':
                    $fee_rate = settings('mollie')->deposit_fee;
                    break;

                // Offline payment
                case 'offline_payment':
                    
                    $response = $this->offline();

                    break;

                // Paymob
                case 'paymob':
                    $fee_rate = settings('paymob')->deposit_fee;
                    break;

                // Paystack
                case 'paystack':
                    
                    // Handle paystack payment
                    $response = $this->paystack($data);

                    break;

                // Paytabs
                case 'paytabs':
                    $fee_rate = settings('paytabs')->deposit_fee;
                    break;

                // Paytr
                case 'paytr':
                    $fee_rate = settings('paytr')->deposit_fee;
                    break;

                // Razorpay
                case 'razorpay':
                    
                    // Handle razorpay payment
                    $response = $this->razorpay($data);

                    break;

                // Vnpay
                case 'vnpay':
                    
                    // Set timezone
                    $tz                        = 'Asia/Ho_Chi_Minh';
                    $timestamp                 = time();
                    $dt                        = new DateTime("now", new DateTimeZone($tz));
                    $dt->setTimestamp($timestamp);
                    $startTime                 = $dt->format('YmdHis');

                    // Set payment gateway settings
                    $vnp_TmnCode               = config('vnpay.tmn_code');
                    $vnp_HashSecret            = config('vnpay.hash_secret');
                    $vnp_Url                   = config('vnpay.api_url');
                    $vnp_Returnurl             = url('account/deposit/callback/vnpay');

                    // Set order details
                    $vnp_TxnRef                = uid();
                    $vnp_OrderInfo             = __('messages.t_deposit');
                    $vnp_OrderType             = "other";
                    $vnp_Amount                = $this->amount * 100;
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

                // Xendit
                case 'xendit':
                    
                    $response = $this->xendit($data);

                    break;

                // Nowpayments
                case 'nowpayments':
                    
                    $response = $this->nowpayments();

                    break;
                
                default:
                    $fee_rate = 0;
                    break;
            }

            // Check if response succeeded
            if (isset($response['success']) && $response['success']) {
                
                // Transaction completed
                $this->isSucceeded = true; 
                
                // Success
                $this->notification([
                    'title'       => __('messages.t_success'),
                    'description' => __('messages.t_ur_transaction_has_completed'),
                    'icon'        => 'success'
                ]);

                // Scroll up
                $this->dispatchBrowserEvent('scrollTo', 'scroll-to-deposit-container');

                // Return
                return;

            } else {

                // Something went wrong
                $this->notification([
                    'title'       => __('messages.t_error'),
                    'description' => $response['message'],
                    'icon'        => 'error'
                ]);

                // Return
                return;

            }

        } catch (\Throwable $th) {

            // Something went wrong
            $this->notification([
                'title'       => __('messages.t_error'),
                'description' => $th->getMessage(),
                'icon'        => 'error'
            ]);

        }
    }


    /**
     * Handle paypal payment
     *
     * @param string $order_id
     * @return mixed
     */
    protected function paypal($order_id)
    {
        try {
            
            // Get default currency exchange rate
            $default_currency_exchange   = settings('currency')->exchange_rate;

            // Get payment gateway exchange rate
            $gateway_currency_exchange   = settings('paypal')->exchange_rate;

            // Get gateway default currency
            $gateway_currency            = config('paypal.currency');

            // Set provider name
            $provider_name               = 'paypal';

            // Set paypal provider and config
            $client                      = new PayPalClient();
    
            // Get paypal access token
            $client->getAccessToken();

            // Capture this order
            $order                       = $client->capturePaymentOrder($order_id);

            // Let's see if payment suuceeded
            if ( is_array($order) && isset($order['status']) && $order['status'] === 'COMPLETED' ) {
                
                // Get paid amount
                $amount                  = $order['purchase_units'][0]['payments']['captures'][0]['amount']['value'];

                // Calculate fee
                $fee                     = $this->calculateFee($amount);

                // Set transaction id
                $transaction_id          = $order['id'];

                // Make transaction
                $deposit                 = new DepositTransaction();
                $deposit->user_id        = auth()->id();
                $deposit->transaction_id = $transaction_id;
                $deposit->payment_method = $provider_name;
                $deposit->amount_total   = round( ($amount * $default_currency_exchange) / $gateway_currency_exchange, 2 );
                $deposit->amount_fee     = round( ($fee * $default_currency_exchange) / $gateway_currency_exchange, 2 );
                $deposit->amount_net     = round( ( ($amount - $fee ) * $default_currency_exchange ) / $gateway_currency_exchange, 2 );
                $deposit->currency       = $gateway_currency;
                $deposit->exchange_rate  = $gateway_currency_exchange;
                $deposit->status         = 'paid';
                $deposit->ip_address     = request()->ip();
                $deposit->save();

                // Add funds to account
                $this->addFunds(round( ( ($amount - $fee ) * $default_currency_exchange ) / $gateway_currency_exchange, 2 ));

                // Set response
                $response = [
                    'success'     => true,
                    'message'     => __('messages.t_deposit_success_subtitle'),
                    'transaction' => $deposit->toArray()
                ];

                // Return response
                return $response;

            } else {

                // We couldn't handle your payment
                $response = [
                    'success'  => false,
                    'message'  => __('messages.t_we_could_not_handle_ur_deposit_payment'),
                    'provider' => $provider_name
                ];

                // Return response
                return $response;

            }

        } catch (\Throwable $th) {
            
            // Something went wrong
            $response = [
                'success'  => false,
                'message'  => $th->getMessage(),
                'provider' => $provider_name
            ];

            // Return response
            return $response;

        }
    }


    /**
     * Handle offline payment
     *
     * @return mixed
     */
    protected function offline()
    {
        try {

            // Get paid amount
            $amount                  = $this->amount;

            // Calculate fee
            $fee                     = $this->calculateFee($amount);

            // Set transaction id
            $transaction_id          = uid();

            // Make transaction
            $deposit                 = new DepositTransaction();
            $deposit->user_id        = auth()->id();
            $deposit->transaction_id = $transaction_id;
            $deposit->payment_method = 'offline_payment';
            $deposit->amount_total   = round( $amount, 2 );
            $deposit->amount_fee     = round( $fee, 2 );
            $deposit->amount_net     = round( $amount - $fee, 2 );
            $deposit->currency       = settings('currency')->code;
            $deposit->exchange_rate  = settings('currency')->exchange_rate;
            $deposit->status         = 'pending';
            $deposit->ip_address     = request()->ip();
            $deposit->save();

            // Set response
            $response = [
                'success'     => true,
                'message'     => __('messages.t_deposit_offline_pending_msg'),
                'transaction' => $deposit->toArray()
            ];
            
            // Return response
            return $response;

        } catch (\Throwable $th) {
            
            // Something went wrong
            $response = [
                'success'  => false,
                'message'  => $th->getMessage(),
                'provider' => 'offline_payment'
            ];

            // Return response
            return $response;

        }
    }


    /**
     * Handle paystack payment
     *
     * @return array
     */
    protected function paystack($reference_id)
    {
        
        try {
            
            // Get default currency exchange rate
            $default_currency_exchange   = settings('currency')->exchange_rate;

            // Get payment gateway exchange rate
            $gateway_currency_exchange   = settings('paystack')->exchange_rate;

            // Get gateway default currency
            $gateway_currency            = settings('paystack')->currency;

            // Set provider name
            $provider_name               = 'paystack';

            // Get paystack secret key
            $paystack_secret_key         = config('paystack.secretKey');

            // Send request
            $client                      = Http::withHeaders([
                'Authorization' => 'Bearer ' . $paystack_secret_key,
                'Accept'        => 'application/json',
            ])->get("https://api.paystack.co/transaction/verify/$reference_id");

            // Convert to json
            $payment                     = $client->json();

            // Let's see if payment suuceeded
            if ( is_array($payment) && isset($payment['status']) && $payment['status'] === true && isset($payment['data']) ) {
                
                // Get paid amount
                $amount                  = $payment['data']['amount'] / 100;

                // Calculate fee
                $fee                     = $this->calculateFee($amount);

                // Set transaction id
                $transaction_id          = $payment['data']['reference'];

                // Make transaction
                $deposit                 = new DepositTransaction();
                $deposit->user_id        = auth()->id();
                $deposit->transaction_id = $transaction_id;
                $deposit->payment_method = $provider_name;
                $deposit->amount_total   = round( ($amount * $default_currency_exchange) / $gateway_currency_exchange, 2 );
                $deposit->amount_fee     = round( ($fee * $default_currency_exchange) / $gateway_currency_exchange, 2 );
                $deposit->amount_net     = round( ( ($amount - $fee ) * $default_currency_exchange ) / $gateway_currency_exchange, 2 );
                $deposit->currency       = $gateway_currency;
                $deposit->exchange_rate  = $gateway_currency_exchange;
                $deposit->status         = 'paid';
                $deposit->ip_address     = request()->ip();
                $deposit->save();

                // Add funds to account
                $this->addFunds(round( ( ($amount - $fee ) * $default_currency_exchange ) / $gateway_currency_exchange, 2 ));

                // Set response
                $response = [
                    'success'     => true,
                    'message'     => __('messages.t_deposit_success_subtitle'),
                    'transaction' => $deposit->toArray()
                ];

                // Return response
                return $response;

            } else {

                // We couldn't handle your payment
                $response = [
                    'success'  => false,
                    'message'  => __('messages.t_we_could_not_handle_ur_deposit_payment'),
                    'provider' => $provider_name
                ];

                // Return response
                return $response;

            }

        } catch (\Throwable $th) {
            
            // Something went wrong
            $response = [
                'success'  => false,
                'message'  => $th->getMessage(),
                'provider' => $provider_name
            ];

            // Return response
            return $response;

        }
    }


    /**
     * Handle mollie payment
     *
     * @return mixed
     */
    protected function mollie()
    {
        try {
            // Set currency
            $currency         = settings('mollie')->currency;

            // Set amount
            $amount           = number_format( $this->amount, 2, '.', '' );

            // Set mollie client
            $mollie           = new \Mollie\Api\MollieApiClient();

            // Set api key
            $mollie->setApiKey(config('mollie.key'));

            // Generate transaction id
            $transaction_id   = Str::uuid();

            // Encrypt amount
            $encrypted_amount = encrypt($this->amount);

            // Create a payment request
            $payment  = $mollie->payments->create([
                "amount" => [
                    "currency" => "$currency",
                    "value"    => "$amount"
                ],
                "description" => __('messages.t_add_funds'),
                "redirectUrl" => url("account/deposit/callback/mollie?t=$transaction_id&a=$encrypted_amount"),
                "webhookUrl"  => url("account/deposit/callback/mollie/webhook?t=$transaction_id&a=$encrypted_amount")
            ]);

            // Redirect to payment link
            return redirect($payment->getCheckoutUrl());

        } catch (\Throwable $th) {
            
            // Error
            return [
                'success' => false,
                'message' => $th->getMessage()
            ];

        }
    }


    /**
     * Handle xendit payment
     *
     * @return array
     */
    protected function xendit($data)
    {
        try {
            
            // Get default currency exchange rate
            $default_currency_exchange   = settings('currency')->exchange_rate;

            // Get payment gateway exchange rate
            $gateway_currency_exchange   = settings('xendit')->exchange_rate;

            // Get gateway default currency
            $gateway_currency            = settings('xendit')->currency;

            // Set provider name
            $provider_name               = 'xendit';

            // Set api secret key
            Xendit::setApiKey(config('xendit.secret_key'));

            $xendit_params = [
                'token_id'          => $data['token'],
                'external_id'       => uid(32),
                'authentication_id' => $data['authentication_id'],
                'amount'            => $this->amount,
                'card_cvn'          => $data['cvn'],
                'capture'           => false
            ];
            
            $payment = \Xendit\Cards::create($xendit_params);

            // Let's see if payment suuceeded
            if ( is_array($payment) && isset($payment['status']) && ($payment['status'] === 'AUTHORIZED' || $payment['status'] === 'CAPTURED') ) {
                
                // Get paid amount
                $amount                  = $payment['authorized_amount'];

                // Calculate fee
                $fee                     = $this->calculateFee($amount);

                // Set transaction id
                $transaction_id          = $payment['id'];

                // Make transaction
                $deposit                 = new DepositTransaction();
                $deposit->user_id        = auth()->id();
                $deposit->transaction_id = $transaction_id;
                $deposit->payment_method = $provider_name;
                $deposit->amount_total   = round( ($amount * $default_currency_exchange) / $gateway_currency_exchange, 2 );
                $deposit->amount_fee     = round( ($fee * $default_currency_exchange) / $gateway_currency_exchange, 2 );
                $deposit->amount_net     = round( ( ($amount - $fee ) * $default_currency_exchange ) / $gateway_currency_exchange, 2 );
                $deposit->currency       = $gateway_currency;
                $deposit->exchange_rate  = $gateway_currency_exchange;
                $deposit->status         = 'paid';
                $deposit->ip_address     = request()->ip();
                $deposit->save();

                // Add funds to account
                $this->addFunds(round( ( ($amount - $fee ) * $default_currency_exchange ) / $gateway_currency_exchange, 2 ));

                // Set response
                $response = [
                    'success'     => true,
                    'message'     => __('messages.t_deposit_success_subtitle'),
                    'transaction' => $deposit->toArray()
                ];

                // Return response
                return $response;

            } else {

                // We couldn't handle your payment
                $response = [
                    'success'  => false,
                    'message'  => __('messages.t_we_could_not_handle_ur_deposit_payment'),
                    'provider' => $provider_name
                ];

                // Return response
                return $response;

            }

        } catch (\Throwable $th) {
            
            // Something went wrong
            $response = [
                'success'  => false,
                'message'  => $th->getMessage(),
                'provider' => $provider_name
            ];

            // Return response
            return $response;

        }
    }


    /**
     * Handle mercadopago payment
     *
     * @return array
     */
    protected function mercadopago($data)
    {
        try {
            
            // Get default currency exchange rate
            $default_currency_exchange   = settings('currency')->exchange_rate;

            // Get payment gateway exchange rate
            $gateway_currency_exchange   = settings('mercadopago')->exchange_rate;

            // Get gateway default currency
            $gateway_currency            = settings('mercadopago')->currency;

            // Set provider name
            $provider_name               = 'mercadopago';

            // Set api secret key
            \MercadoPago\SDK::setAccessToken(config('mercadopago.access_token'));

            // Create new chanrge
            $payment                     = new \MercadoPago\Payment();
            $payment->transaction_amount = $this->amount;
            $payment->token              = $data['token_id'];
            $payment->description        = __('messages.t_add_funds');
            $payment->installments       = $data['installments'];
            $payment->payment_method_id  = $data['payment_method_id'];
            $payment->payer              = ["email" => $data['payer_email']];
            $payment->save();

            // Let's see if payment suuceeded
            if ( $payment && $payment->status === 'approved' ) {
                
                // Get paid amount
                $amount                  = $payment->transaction_amount;

                // Calculate fee
                $fee                     = $this->calculateFee($amount);

                // Set transaction id
                $transaction_id          = $payment->id;

                // Make transaction
                $deposit                 = new DepositTransaction();
                $deposit->user_id        = auth()->id();
                $deposit->transaction_id = $transaction_id;
                $deposit->payment_method = $provider_name;
                $deposit->amount_total   = round( ($amount * $default_currency_exchange) / $gateway_currency_exchange, 2 );
                $deposit->amount_fee     = round( ($fee * $default_currency_exchange) / $gateway_currency_exchange, 2 );
                $deposit->amount_net     = round( ( ($amount - $fee ) * $default_currency_exchange ) / $gateway_currency_exchange, 2 );
                $deposit->currency       = $gateway_currency;
                $deposit->exchange_rate  = $gateway_currency_exchange;
                $deposit->status         = 'paid';
                $deposit->ip_address     = request()->ip();
                $deposit->save();

                // Add funds to account
                $this->addFunds(round( ( ($amount - $fee ) * $default_currency_exchange ) / $gateway_currency_exchange, 2 ));

                // Set response
                $response = [
                    'success'     => true,
                    'message'     => __('messages.t_deposit_success_subtitle'),
                    'transaction' => $deposit->toArray()
                ];

                // Return response
                return $response;

            } else {

                // We couldn't handle your payment
                $response = [
                    'success'  => false,
                    'message'  => __('messages.t_we_could_not_handle_ur_deposit_payment'),
                    'provider' => $provider_name
                ];

                // Return response
                return $response;

            }

        } catch (\Throwable $th) {
            
            // Something went wrong
            $response = [
                'success'  => false,
                'message'  => $th->getMessage(),
                'provider' => $provider_name
            ];

            // Return response
            return $response;

        }
    }


    /**
     * Handle vnpay payment
     *
     * @return object
     */
    protected function vnpay()
    {
        try {
            
            // Get api url
            $api_url     = config('vnpay.api_url');

            // Get Terminal id
            $tmn_code    = config('vnpay.tmn_code');

            // Get hash secret
            $hash_secret = config('vnpay.hash_secret');

            // Set return url
            $return_url  = url('account/deposit/callback/vnpay');

            // Set amount
            $amount      = $this->amount * 100;

            // Set currency
            $currency    = "VND";

            // Get ip address
            $ip_address  = request()->ip();

            // Generate ref id
            $ref_id      = time();

            // Generate params
            $params      = array(
                "vnp_Version"        => "2.1.0",
                "vnp_Command"        => "pay",
                "vnp_TmnCode"        => $tmn_code,
                "vnp_Amount"         => $amount,
                "vnp_CreateDate"     => date('YmdHis'),
                "vnp_CurrCode"       => $currency,
                "vnp_IpAddr"         => $ip_address,
                "vnp_Locale"         => "vn",
                "vnp_OrderInfo"      => __('messages.t_add_funds'),
                "vnp_ReturnUrl"      => $return_url,
                "vnp_TxnRef"         => $ref_id
            );

            // Sort array by key
            ksort($params);

            // Set empty query string
            $query    = "";

            // Start at 0
            $i        = 0;

            // hashed value
            $hashdata = "";

            // Loop trough params
            foreach ($params as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            // Generate url to redirect
            $redirect = $api_url . "?" . $query . "&vnp_SecureHash=" . hash('sha256', $hash_secret);

            // Redirect
            return redirect($redirect);

        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * Handle Razorpay payment
     *
     * @param array $data
     * @return array
     */
    protected function razorpay($data)
    {
        try {
            
            // Get default currency exchange rate
            $default_currency_exchange = settings('currency')->exchange_rate;

            // Get payment gateway exchange rate
            $gateway_currency_exchange = settings('razorpay')->exchange_rate;

            // Get gateway default currency
            $gateway_currency          = settings('razorpay')->currency;

            // Set provider name
            $provider_name             = 'razorpay';

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
                    'amount'   => $this->amount * 100,
                    'currency' => settings('razorpay')->currency
                ]);

            } else if ($fetch_payment->status === 'captured') {
                
                // Set payment
                $payment = $fetch_payment;

            } else {

                // We couldn't handle your payment
                $response = [
                    'success'  => false,
                    'message'  => __('messages.t_we_could_not_handle_ur_deposit_payment'),
                    'provider' => $provider_name
                ];

                // Return response
                return $response;

            }

            // Let's see if payment suuceeded
            if ( $payment && $payment->status === 'captured' ) {
                
                // Get paid amount
                $amount                  = $payment->amount / 100;

                // Calculate fee
                $fee                     = $this->calculateFee($amount);

                // Set transaction id
                $transaction_id          = $payment->id;

                // Make transaction
                $deposit                 = new DepositTransaction();
                $deposit->user_id        = auth()->id();
                $deposit->transaction_id = $transaction_id;
                $deposit->payment_method = $provider_name;
                $deposit->amount_total   = round( ($amount * $default_currency_exchange) / $gateway_currency_exchange, 2 );
                $deposit->amount_fee     = round( ($fee * $default_currency_exchange) / $gateway_currency_exchange, 2 );
                $deposit->amount_net     = round( ( ($amount - $fee ) * $default_currency_exchange ) / $gateway_currency_exchange, 2 );
                $deposit->currency       = $gateway_currency;
                $deposit->exchange_rate  = $gateway_currency_exchange;
                $deposit->status         = 'paid';
                $deposit->ip_address     = request()->ip();
                $deposit->save();

                // Add funds to account
                $this->addFunds(round( ( ($amount - $fee ) * $default_currency_exchange ) / $gateway_currency_exchange, 2 ));

                // Set response
                $response = [
                    'success'     => true,
                    'message'     => __('messages.t_deposit_success_subtitle'),
                    'transaction' => $deposit->toArray()
                ];

                // Return response
                return $response;

            } else {

                // We couldn't handle your payment
                $response = [
                    'success'  => false,
                    'message'  => __('messages.t_we_could_not_handle_ur_deposit_payment'),
                    'provider' => $provider_name
                ];

                // Return response
                return $response;

            }

        } catch (\Throwable $th) {
            
            // Something went wrong
            $response = [
                'success'  => false,
                'message'  => $th->getMessage(),
                'provider' => $provider_name
            ];

            // Return response
            return $response;

        }
    }


    /**
     * Handle Nowpayments payment
     *
     * @return array
     */
    protected function nowpayments()
    {
        try {
            
            // Get default currency exchange rate
            $default_currency_exchange = settings('currency')->exchange_rate;

            // Get payment gateway exchange rate
            $gateway_currency_exchange = settings('nowpayments')->exchange_rate;

            // Get gateway default currency
            $gateway_currency          = settings('nowpayments')->currency;

            $client  = new Client();
            $headers = [
                'x-api-key'    => config('nowpayments.api_key'),
                'Content-Type' => 'application/json'
            ];
            $request = new Request('GET', config('nowpayments.payment_url') . '/' . $this->nowpayments_payment_id, $headers);
            $res     = $client->sendAsync($request)->wait();
            $data    = json_decode($res->getBody(), true);

            // Set provider name
            $provider_name = 'nowpayments';

            // Let's see if payment suuceeded
            if ( is_array($data) && isset($data['payment_status']) && $data['payment_status'] == 'finished' ) {
                
                // Get paid amount
                $amount                  = $data['price_amount'];

                // Calculate fee
                $fee                     = $this->calculateFee($amount);

                // Set transaction id
                $transaction_id          = $data['payment_id'];

                // Make transaction
                $deposit                 = new DepositTransaction();
                $deposit->user_id        = auth()->id();
                $deposit->transaction_id = $transaction_id;
                $deposit->payment_method = $provider_name;
                $deposit->amount_total   = round( ($amount * $default_currency_exchange) / $gateway_currency_exchange, 2 );
                $deposit->amount_fee     = round( ($fee * $default_currency_exchange) / $gateway_currency_exchange, 2 );
                $deposit->amount_net     = round( ( ($amount - $fee ) * $default_currency_exchange ) / $gateway_currency_exchange, 2 );
                $deposit->currency       = $gateway_currency;
                $deposit->exchange_rate  = $gateway_currency_exchange;
                $deposit->status         = 'paid';
                $deposit->ip_address     = request()->ip();
                $deposit->save();

                // Add funds to account
                $this->addFunds(round( ( ($amount - $fee ) * $default_currency_exchange ) / $gateway_currency_exchange, 2 ));

                // Set response
                $response = [
                    'success'     => true,
                    'message'     => __('messages.t_deposit_success_subtitle'),
                    'transaction' => $deposit->toArray()
                ];

                // Return response
                return $response;

            } else {

                // We couldn't handle your payment
                $response = [
                    'success'  => false,
                    'message'  => __('messages.t_we_could_not_handle_ur_deposit_payment'),
                    'provider' => $provider_name
                ];

                // Return response
                return $response;

            }

        } catch (\Throwable $th) {
            
            // Something went wrong
            $response = [
                'success'  => false,
                'message'  => $th->getMessage(),
                'provider' => $provider_name
            ];

            // Return response
            return $response;

        }
    }


    /**
     * Get paymob payment token
     *
     * @return array
     */
    protected function getPayMobPaymentKey()
    {
        try {
            
            // Get auth token
            $auth    = Http::acceptJson()->post('https://accept.paymob.com/api/auth/tokens', [
                                'api_key' => config('paymob.api_key'),
                            ])->json();
        
            // Create order
            $order   = Http::acceptJson()->post('https://accept.paymob.com/api/ecommerce/orders', [
                                'auth_token'      => $auth['token'],
                                'delivery_needed' => false,
                                'amount_cents'    => $this->amount * 100,
                                'items'           => []
                            ])->json();
        
            // Make payment
            $payment = Http::acceptJson()->post('https://accept.paymob.com/api/acceptance/payment_keys', [
                                'auth_token'     => $auth['token'],
                                'amount_cents'   => $this->amount * 100,
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
        
            // Set payment token
            $this->paymob_payment_token = $payment['token'];

            // Success
            return [
                'success' => true
            ];

        } catch (\Throwable $th) {
            
            // Error
            return  [
                'success' => false,
                'message' => $th->getMessage()
            ];

        }
    }


    /**
     * Add funds
     *
     * @param float $amount
     * @return void
     */
    protected function addFunds($amount)
    {
        try {
            
            // Get user
            $user                    = User::where('id', auth()->id())->first();

            // Add funds
            $user->balance_available = $user->balance_available + $amount;
            $user->save();

        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * Generate sripe intent key
     *
     * @return void
     */
    protected function getStripeIntent()
    {
        // Set your secret key. Remember to switch to your live secret key in production.
        $stripe = new \Stripe\StripeClient(config('stripe.secret_key'));

        $intent = $stripe->paymentIntents->create(
            [
                'amount'                    => $this->amount * 100,
                'currency'                  => settings('stripe')->currency,
                'automatic_payment_methods' => ['enabled' => true],
            ]
        );

        $this->stripe_intent_secret = $intent->client_secret;
    }


    /**
     * Save deposit for webhook callback
     *
     * @param array $data
     * @return void
     */
    protected function depositWebhook($data)
    {
        try {
            
            // Set user id
            $user_id                = auth()->id();
            
            // Set amount
            $amount                    = $this->amount;

            // Set payment id
            $payment_id              = $data['payment_id'];

            // Set payment method 
            $payment_method          = $data['payment_method'];

            // Save
            $webhook                 = new DepositWebhook();
            $webhook->payment_id     = $payment_id;
            $webhook->payment_method = $payment_method;
            $webhook->amount         = $amount;
            $webhook->user_id        = $user_id;
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
            $this->amount * 100,
            // String currency (required): Uppercase currency.
            $settings->currency,
            // String customerIP (required): Customer Address IP.
            request()->ip(),
            // String successUrl (required): This URL is returned when the payment is successfully processed.
            url('account/deposit/callback/youcanpay'),
            // String errorUrl (required): This URL is returned when payment is invalid.
            url('account/deposit/callback/youcanpay'),
            // Array customerInfo (optional): Data of the customer who wishes to make this purchase.
            $customerInfo,
            // Array metadata (optional): You can use it to send data to retrieve after the response or in the webhook.
            $metadata
        );

        // Redirect to payment gateway
        return $token->getPaymentURL(app()->getLocale());
    }

}