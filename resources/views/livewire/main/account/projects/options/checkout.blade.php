<div class="grid grid-cols-1 md:grid-cols-2 lg:gap-x-5 gap-y-5">

    {{-- Checkout form --}}
    <div>

        <div class="rounded-lg bg-white shadow border-gray-50 border p-5 relative mb-4">

            {{-- Loading --}}
            <div wire:loading wire:loading.block>
                <div class="absolute w-full top-0 h-full -mx-5 flex items-center justify-center bg-black bg-opacity-50 z-50 rounded-md">
                    <div class="lds-ripple"><div></div><div></div></div>
                </div>
            </div>

            {{-- Section heading --}}
            <h4 class="text-base text-zinc-700 font-bold mb-1">@lang('messages.t_promote_project')</h4>
            <p class="leading-relaxed text-gray-500 mb-5 text-sm">
                @lang('messages.t_promote_project_subtitle')
            </p>

            {{-- Select a payment method --}}
            <h5 class="flex items-center my-8">
                <span class="text-xs uppercase tracking-wide text-primary-600 font-semibold ltr:mr-3 rtl:ml-3">
                    @lang('messages.t_select_payment_method')    
                </span> 
                <span aria-hidden="true" class="grow bg-gray-100 rounded h-0.5"></span>
            </h5>

            {{-- Enabed payment mathods list --}}
            @if (!$selected_payment_method)
                <fieldset class="mt-4">
                    <legend class="sr-only">
                        @lang('messages.t_select_payment_method')    
                    </legend>
                    <div class="space-y-5">

                        {{-- Paypal --}}
                        @if (settings('paypal')->is_enabled)
                            <div class="flex items-center">
                                <input id="selected_payment_method_paypal" name="selected_payment_method" wire:model="selected_payment_method" value="paypal" type="radio" class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300">
                                <label for="selected_payment_method_paypal" class="flex items-center ltr:ml-3 rtl:mr-3 cursor-pointer">
                                    <span class="block text-sm font-semibold text-zinc-700"> 
                                        {{ settings('paypal')->name }}    
                                    </span>
                                </label>
                            </div>
                        @endif
                        
                    </div>
                </fieldset>
            @endif

            
            {{-- Check selected payment method --}}
            @switch($selected_payment_method)

                {{-- PayPal --}}
                @case('paypal')

                    {{-- Calculate exchange rate --}}
                    @php
                        
                        $gateway_exchange_rate = (float)settings('paypal')->exchange_rate;
                        $exchange_total_amount = $this->calculateExchangeAmount($gateway_exchange_rate);

                    @endphp
                    
                    <div class="w-full md:max-w-xs mx-auto mt-12 block">

                        {{-- Paypal button --}}
                        <div id="paypal-button-container" wire:ignore></div>

                        <script>
                            // Render the PayPal button into #paypal-button-container
                            paypal.Buttons({

                                // Set up the transaction
                                createOrder: function(data, actions) {
                                    return actions.order.create({
                                        purchase_units: [{
                                            amount: {
                                                value: '{{ $exchange_total_amount }}'
                                            }
                                        }]
                                    });
                                },

                                // Finalize the transaction
                                onApprove: function(data, actions) {

                                    @this.checkout(data.orderID);

                                }

                                }).render('#paypal-button-container');
                        </script>

                    </div>

                    @break
                    
                @default
                    
            @endswitch

        </div>

        {{-- Order summary --}}
        <div class="w-full">
            <div class="bg-gray-100 rounded-lg py-6 px-6 lg:py-8">

                <h1 class="font-bold text-base text-zinc-900 mb-6">@lang('messages.t_selected_upgrades')</h1>

                <div class="divide-y divide-gray-200 text-sm lg:mt-0">

                    {{-- Urgent plan --}}
                    @if ($subscription->project->is_urgent)
                        @php
                            $urgent = \App\Models\ProjectPlan::whereType('urgent')->first();
                        @endphp
                        @if ($urgent)
                            <div class="pb-4 flex items-center justify-between">
                                <dt class="text-gray-600">
                                    <span class="font-semibold px-3 py-1 rounded-sm text-[13px] tracking-wide" style="color: {{ $urgent->badge_text_color }};background-color: {{ $urgent->badge_bg_color }}">{{ $urgent->title }}</span>
                                    <p class="text-[13px] mt-2">{{ $urgent->description }}</p>    
                                </dt>
                                <dd class="font-medium text-gray-900 ltr:pl-5 rtl:pr-5">
                                    @money($urgent->price, settings('currency')->code, true)
                                </dd>
                            </div>
                        @endif
                    @endif

                    {{-- Highlight plan --}}
                    @if ($subscription->project->is_highlighted)
                        @php
                            $highlight = \App\Models\ProjectPlan::whereType('highlight')->first();
                        @endphp
                        @if ($highlight)
                            <div class="py-4 flex items-center justify-between">
                                <dt class="text-gray-600">
                                    <span class="font-semibold px-3 py-1 rounded-sm text-[13px] tracking-wide" style="color: {{ $highlight->badge_text_color }};background-color: {{ $highlight->badge_bg_color }}">{{ $highlight->title }}</span>
                                    <p class="text-[13px] mt-2">{{ $highlight->description }}</p>    
                                </dt>
                                <dd class="font-medium text-gray-900 ltr:pl-5 rtl:pr-5">
                                    @money($highlight->price, settings('currency')->code, true)
                                </dd>
                            </div>
                        @endif
                    @endif

                    {{-- Featured plan --}}
                    @if ($subscription->project->is_featured)
                        @php
                            $featured = \App\Models\ProjectPlan::whereType('featured')->first();
                        @endphp
                        @if ($featured)
                            <div class="py-4 flex items-center justify-between">
                                <dt class="text-gray-600">
                                    <span class="font-semibold px-3 py-1 rounded-sm text-[13px] tracking-wide" style="color: {{ $featured->badge_text_color }};background-color: {{ $featured->badge_bg_color }}">{{ $featured->title }}</span>
                                    <p class="text-[13px] mt-2">{{ $featured->description }}</p>    
                                </dt>
                                <dd class="font-medium text-gray-900 ltr:pl-5 rtl:pr-5">
                                    @money($featured->price, settings('currency')->code, true)
                                </dd>
                            </div>
                        @endif
                    @endif

                    {{-- Alert plan --}}
                    @if ($subscription->project->is_alert)
                        @php
                            $alert = \App\Models\ProjectPlan::whereType('alert')->first();
                        @endphp
                        @if ($alert)
                            <div class="py-4 flex items-center justify-between">
                                <dt class="text-gray-600">
                                    <span class="font-semibold px-3 py-1 rounded-sm text-[13px] tracking-wide" style="color: {{ $alert->badge_text_color }};background-color: {{ $alert->badge_bg_color }}">{{ $alert->title }}</span>
                                    <p class="text-[13px] mt-2">{{ $alert->description }}</p>
                                </dt>
                                <dd class="font-medium text-gray-900 ltr:pl-5 rtl:pr-5">
                                    @money($alert->price, settings('currency')->code, true)
                                </dd>
                            </div>
                        @endif
                    @endif

                    {{-- Total --}}
                    <div class="pt-4 flex items-center justify-between">
                        <dt class="font-medium text-gray-900">@lang('messages.t_total')</dt>
                        <dd class="font-medium text-primary-600">
                            @money($subscription->total, settings('currency')->code, true)
                        </dd>
                    </div>

                </div>
            </div>
            
            {{-- Secure payment --}}
            <div class="flex items-center space-x-2 rtl:space-x-reverse justify-center mt-5 text-green-500">
                <svg class="w-6 h-6" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M20.995 6.9a.998.998 0 0 0-.548-.795l-8-4a1 1 0 0 0-.895 0l-8 4a1.002 1.002 0 0 0-.547.795c-.011.107-.961 10.767 8.589 15.014a.987.987 0 0 0 .812 0c9.55-4.247 8.6-14.906 8.589-15.014zM12 19.897C5.231 16.625 4.911 9.642 4.966 7.635L12 4.118l7.029 3.515c.037 1.989-.328 9.018-7.029 12.264z"></path><path d="m11 12.586-2.293-2.293-1.414 1.414L11 15.414l5.707-5.707-1.414-1.414z"></path></svg>
                <span class="text-sm font-medium">@lang('messages.t_ur_transaction_is_secure')</span>
            </div>
        </div>

    </div>

    {{-- Project preview --}}
    <div class="rounded-lg bg-white shadow border-gray-50 border p-5 h-fit">

        {{-- Title --}}
        <a href="{{ url('project/' . $subscription->project->pid . '/' . $subscription->project->slug) }}" target="_blank" class="block mb-4">
            <h1 class="font-semibold text-base text-zinc-700 hover:underline hover:text-primary-600 transition-all duration-200">{{ $subscription->project->title }}</h1>
        </a>
    
        {{-- Details --}}
        <dl class="flex-1 grid grid-cols-2 gap-x-6 text-sm sm:col-span-3 sm:grid-cols-3 lg:col-span-2">
    
            {{-- Status --}}
            <div>
                <dt class="font-normal text-gray-500 text-xs">@lang('messages.t_status')</dt>
                <dd class="text-[13px] font-medium mt-1 text-amber-700">@lang('messages.t_pending_payment')</dd>
            </div>
    
            {{-- Posted date --}}
            <div class="hidden sm:block">
                <dt class="font-normal text-gray-500 text-xs">@lang('messages.t_posted_date')</dt>
                <dd class="text-[13px] font-medium mt-1 text-gray-700">
                    <span>{{ format_date($subscription->project->created_at, 'ago') }}</span>
                </dd>
            </div>
    
            {{-- Budget --}}
            <div>
                <dt class="font-normal text-gray-500 text-xs">@lang('messages.t_budget')</dt>
                <dd class="mt-1 text-[13px] font-medium text-gray-700">
                    @money($subscription->project->budget_min, settings('currency')->code, true)
                    <span class="text-gray-300 mx-1">/</span>
                    @money($subscription->project->budget_max, settings('currency')->code, true)
                </dd>
            </div>
    
        </dl>
    
        {{-- Description --}}
        <div class="my-4 block leading-relaxed text-gray-500 text-sm">
            {{ add_3_dots($subscription->project->description, 100) }}
        </div>
    
        {{-- Skills --}}
        <div class="mt-4 flex items-center space-x-2 rtl:space-x-reverse">
            @foreach ($subscription->project->skills as $skill)
                <div class="bg-gray-100 hover:bg-primary-100 text-gray-800 hover:text-primary-600 text-xs font-medium ltr:mr-2 rtl:ml-2 ltr:first:mr-0 rtl:first:ml-0 px-2.5 py-1 rounded dark:bg-gray-700 dark:text-gray-400 border border-gray-500 hover:border-primary-600 transition-colors duration-200">
                    {{ $skill->skill->name }}
                </div>
            @endforeach
        </div>
    
    </div>

</div>

@push('styles')

    {{-- PayPal SDK --}}
    @if (settings('paypal')->is_enabled)

        {{-- Get client id and curency --}}
        @php
            $paypal_client_id = config('paypal.mode') === 'sandbox' ? config('paypal.sandbox.client_id') : config('paypal.live.client_id');
            $currency         = config('paypal.currency');
        @endphp

        {{-- SDK --}}
        <script src="https://www.paypal.com/sdk/js?client-id={{ $paypal_client_id }}&currency={{ $currency }}"></script>

    @endif

@endpush