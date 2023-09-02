<div class="container mx-auto" x-data="window.owsKjTQzMGrSHko">
    <div class="min-h-full bg-white dark:bg-zinc-800 shadow rounded-md">
        
        {{-- Section heading --}}
        <header class="border-b-2 border-gray-100 dark:border-zinc-700 py-8 rounded-t-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 xl:flex xl:items-center xl:justify-between">
                <div class="flex-1 min-w-0">

                    {{-- Breadcrumb --}}
                    <nav class="flex" aria-label="Breadcrumb">
                        <ol role="list" class="flex items-center space-x-4 rtl:space-x-reverse">

                            {{-- Main homepage --}}
                            <li>
                                <div>
                                    <a href="{{ url('/') }}" class="text-sm font-medium text-gray-500 dark:text-gray-300 dark:hover:text-gray-200 hover:text-gray-700">{{ __('messages.t_home') }}</a>
                                </div>
                            </li>

                            {{-- Seller dashboard --}}
                            <li>
                                <div class="flex items-center">

                                    {{-- LTR --}}
                                    <svg class="hidden ltr:block flex-shrink-0 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"> <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg>

                                    {{-- RTL --}}
                                    <svg xmlns="http://www.w3.org/2000/svg" class="hidden rtl:block flex-shrink-0 h-5 w-5 text-gray-400 viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>

                                    <a href="{{ url('seller/home') }}" class="ltr:ml-4 rtl:mr-4 text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-gray-200">{{ __('messages.t_dashboard') }}</a>
                                </div>
                            </li>
                        </ol>
                    </nav>

                    {{-- Title --}}
                    <h1 class="mt-2 text-xl font-bold leading-7 text-gray-900 dark:text-gray-100 sm:text-2xl sm:truncate">{{ __('messages.t_withdrawal_settings') }}</h1>

                    {{-- Quick stats --}}
                    <div class="mt-1 flex flex-col sm:flex-row sm:flex-wrap sm:mt-0 sm:space-x-8 rtl:space-x-reverse">

                        {{-- User level --}}
                        <div class="mt-2 flex items-center text-xs font-medium" style="color: {{ auth()->user()->level->level_color }}">
                            <svg class="flex-shrink-0 ltr:mr-1.5 rtl:ml-1.5 h-4 w-4" style="color: {{ auth()->user()->level->level_color }};margin-top: -3px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"> <path fill-rule="evenodd" d="M6 6V5a3 3 0 013-3h2a3 3 0 013 3v1h2a2 2 0 012 2v3.57A22.952 22.952 0 0110 13a22.95 22.95 0 01-8-1.43V8a2 2 0 012-2h2zm2-1a1 1 0 011-1h2a1 1 0 011 1v1H8V5zm1 5a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1z" clip-rule="evenodd"/> <path d="M2 13.692V16a2 2 0 002 2h12a2 2 0 002-2v-2.308A24.974 24.974 0 0110 15c-2.796 0-5.487-.46-8-1.308z"/> </svg>
                            {{ auth()->user()->level->title }}
                        </div>

                        {{-- Succeeded sales --}}
                        <div class="mt-2 flex items-center text-xs text-gray-500 font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" class="flex-shrink-0 ltr:mr-1.5 rtl:ml-1.5 h-4 w-4 -mt-[2px] text-gray-400" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/></svg>
                            {{ __('messages.t_total_sales_number', ['number' => number_format(auth()->user()->sales->where('status', 'delivered')->where('is_finished', true)->count())]) }}
                        </div>

                        {{-- Country --}}
                        @if (auth()->user()->country)
                            <div class="mt-2 flex items-center text-xs text-gray-500 font-medium">
                                <svg class="flex-shrink-0 ltr:mr-1.5 rtl:ml-1.5 h-4 w-4 -mt-[2px] text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"> <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/> </svg>
                                {{ auth()->user()->country->name }}
                            </div>
                        @endif

                        {{-- Sign up date --}}
                        <div class="mt-2 flex items-center text-xs text-gray-500 font-medium">
                            <svg class="flex-shrink-0 ltr:mr-1.5 rtl:ml-1.5 h-4 w-4 -mt-[2px] text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"> <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/> </svg>
                            {{ __('messages.t_signed_up_on_date', ['date' => format_date(auth()->user()->created_at, 'F j, Y')]) }}
                        </div>
                    
                    </div>

                </div>
                <div class="mt-5 flex xl:mt-0 xl:ltr:ml-4 xl:rtl:mr-4">

                    {{-- Make withdrawal --}}
                    <span class="block ltr:ml-3 rtl:mr-3">
                        <a href="{{ url('seller/withdrawals/create') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-zinc-500 rounded-sm shadow-sm text-[13px] font-medium text-gray-700 dark:text-zinc-200 bg-white dark:bg-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-primary-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 ltr:mr-2 rtl:ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                            {{ __('messages.t_make_withdrawal') }}
                        </a>
                    </span>
        
                    {{-- Withdrawal history --}}
                    <span class="block ltr:ml-3 rtl:mr-3">
                        <a href="{{ url('seller/withdrawals') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-zinc-500 rounded-sm shadow-sm text-[13px] font-medium text-gray-700 dark:text-zinc-200 bg-white dark:bg-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-50 focus:ring-primary-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 ltr:mr-2 rtl:ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            {{ __('messages.t_withdrawals_history') }}
                        </a>
                    </span>
                    
                </div>
            </div>
        </header>
      
        <main class="py-12 px-10" x-data="{ selected: '{{ $selected }}' }">

            {{-- Select payment method --}}
            <div class="space-y-2 mb-10">
                <div class="font-semibold dark:text-gray-100 text-sm tracking-wide">{{ __('messages.t_select_payout_method') }}</div>
                <div class="mt-2">

                    {{-- Paypal --}}
                    @if (settings('paypal')->is_enabled)
                        <label class="inline-flex items-center">
                            <input x-model="selected" value="paypal" type="radio" class="border border-gray-200 dark:border-zinc-700 dark:bg-zinc-600 dark:focus:ring-primary-600 focus:outline-none h-4 w-4 text-primary-600 focus:border-primary-600 focus:ring focus:ring-primary-600 focus:ring-opacity-50">
                            <span class="ltr:ml-2 rtl:mr-2 text-[13px] font-medium dark:text-gray-200">{{ settings('paypal')->name }}</span>
                        </label>
                    @endif

                    {{-- Offline --}}
                    @if (settings('offline_payment')->is_enabled)
                        <label class="inline-flex items-center ml-6">
                            <input x-model="selected" value="offline" type="radio" class="border border-gray-200 dark:border-zinc-700 dark:bg-zinc-600 dark:focus:ring-primary-600 focus:outline-none h-4 w-4 text-primary-600 focus:border-primary-600 focus:ring focus:ring-primary-600 focus:ring-opacity-50">
                            <span class="ltr:ml-2 rtl:mr-2 text-[13px] font-medium dark:text-gray-200">{{ settings('offline_payment')->name }}</span>
                        </label>
                    @endif

                </div>
            </div>

            {{-- Paypal --}}
            @if (settings('paypal')->is_enabled)
                <div x-show="selected == 'paypal'" x-cloak>
                    <div class="grid grid-cols-12 md:gap-x-6 gap-y-6">

                        {{-- Email address --}} 
                        <div class="col-span-12">
                            <x-forms.text-input 
                                :label="__('messages.t_paypal_email')"
                                :placeholder="__('messages.t_enter_paypal_email')"
                                model="paypal_email"
                                type="email"
                                icon="at" />
                        </div>
        
                        {{-- Save --}}
                        <div class="col-span-12">
                            <x-forms.button action="update('paypal')" :text="__('messages.t_update')" :block="true" />
                        </div>
        
                    </div>
                </div>
            @endif

            {{-- Offline payout --}}
            @if (settings('offline_payment')->is_enabled)
                <div x-show="selected == 'offline'" x-cloak>
                    <div class="grid grid-cols-12 md:gap-x-6 gap-y-6">

                        {{-- Bank info --}} 
                        <div class="col-span-12">
                            <x-forms.textarea 
                                :label="settings('offline_payment')->name"
                                placeholder=""
                                model="offline_info"
                                :rows="10"
                                icon="bank" />
                        </div>
        
                        {{-- Save --}}
                        <div class="col-span-12">
                            <x-forms.button action="update('offline')" :text="__('messages.t_update')" :block="true" />
                        </div>
        
                    </div>
                </div>
            @endif
            
        </main>
        
    </div>
</div>

@push('scripts')
    
    {{-- AlpineJs --}}
    <script>
        function owsKjTQzMGrSHko() {
            return {

            }
        }
        window.owsKjTQzMGrSHko = owsKjTQzMGrSHko();
    </script>

@endpush