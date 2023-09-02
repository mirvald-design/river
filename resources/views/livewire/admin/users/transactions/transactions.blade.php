<div class="flex flex-col rounded-lg shadow bg-white overflow-hidden">

    {{-- Loading Indicator --}}
    <div wire:loading wire:loading.block wire:target="approve">
        <div class="absolute w-full h-full flex items-center justify-center bg-black bg-opacity-50 z-50 rounded-lg">
            <div class="lds-ripple"><div></div><div></div></div>
        </div>
    </div>
   
    {{-- Section header --}}
    <div class="py-4 px-5 lg:px-6 w-full sm:flex sm:justify-between sm:items-center">
        <div class="flex justify-center sm:justify-left">
            <h3 class="inline-flex items-center font-semibold">
                <span>@lang('messages.t_transactions_history')</span>
            </h3>
        </div>
    </div>
    
    {{-- Section content --}}
    <div class="grow w-full">
        <div class="overflow-x-auto min-w-full bg-white">
            <table class="min-w-full text-sm align-middle whitespace-nowrap">
                
                <thead>
                    <tr>
                        <th class="px-7 py-3 text-slate-600 bg-slate-100 font-bold text-[10px] uppercase tracking-widest ltr:text-left rtl:text-right">
                            @lang('messages.t_user')
                        </th>
                        <th class="px-7 py-3 text-slate-600 bg-slate-100 font-bold text-[10px] uppercase tracking-widest ltr:text-left rtl:text-right">
                            @lang('messages.t_payment_method')
                        </th>
                        <th class="p-3 text-slate-600 bg-slate-100 font-bold text-[10px] uppercase tracking-widest text-center">
                            @lang('messages.t_amount')
                        </th>
                        <th class="p-3 text-slate-600 bg-slate-100 font-bold text-[10px] uppercase tracking-widest text-center">
                            @lang('messages.t_date')
                        </th>
                        <th class="p-3 text-slate-600 bg-slate-100 font-bold text-[10px] uppercase tracking-widest text-center">
                            @lang('messages.t_status')
                        </th>
                        <th class="p-3 text-slate-600 bg-slate-100 font-bold text-[10px] uppercase tracking-widest text-center">
                            @lang('messages.t_options')
                        </th>
                    </tr>
                </thead>
            
                <tbody>
                    @foreach ($transactions as $t)
                        <tr wire:key="users-transactions-{{ $t->uid }}">

                            {{-- User --}}
                            <td class="px-7 py-3 ltr:text-left rtl:text-right">
                                <a href="{{ url('profile', $t->user->username) }}" target="_blank" class="flex items-center">
                                    <div class="w-8 h-8">
                                        <img class="w-full h-full rounded object-cover lazy" src="{{ placeholder_img() }}" data-src="{{ src($t->user->avatar) }}" alt="{{ $t->user->username }}" />
                                    </div>
                                    <div class="ltr:pl-4 rtl:pr-4">
                                        <p class="font-medium text-xs flex items-center">
                                            {{ $t->user->username }}
                                            @if ($t->user->status === 'verified')
                                                <img data-tooltip-target="tooltip-account-verified-{{ $t->id }}" class="ltr:ml-0.5 rtl:mr-0.5 h-3.5 w-3.5 -mt-0.5" src="{{ url('public/img/auth/verified-badge.svg') }}" alt="{{ __('messages.t_account_verified') }}">
                                                <div id="tooltip-account-verified-{{ $t->id }}" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-xs font-medium text-white bg-gray-900 rounded-sm shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                    {{ __('messages.t_account_verified') }}
                                                </div>
                                            @endif
                                        </p>
                                        <p class="text-[11px] leading-3 text-gray-600 pt-2">{{ $t->user->email }}</p>
                                    </div>
                                </a>
                            </td>

                            {{-- Payment method --}}
                            <td class="px-7 py-3 ltr:text-left rtl:text-right">
                                <span class="text-gray-500 font-semibold text-[13px]">
                                    {{ settings($t->payment_method)?->name }}
                                </span>
                            </td>

                            {{-- Amount --}}
                            <td class="p-3 text-center">
                                <span class="text-gray-500 font-semibold text-[13px]">
                                    @money($t->amount_total, settings('currency')->code, true)    
                                </span>
                            </td>

                            {{-- Date --}}
                            <td class="p-3 text-center font-bold">
                                <span class="text-gray-500 font-semibold text-[13px]">
                                    {{ format_date($t->created_at, 'ago') }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td class="p-3 text-center">
                                @switch($t->status)
                                    @case('paid')
                                        <span class="px-4 py-1 text-xs rounded-2xl font-semibold text-green-500 bg-green-50">
                                            {{ __('messages.t_paid') }}
                                        </span>
                                        @break
                                    @case('pending')
                                        <span class="px-4 py-1 text-xs rounded-2xl font-semibold text-amber-500 bg-amber-50">
                                            {{ __('messages.t_pending') }}
                                        </span>
                                        @break
                                    @case('rejected')
                                        <span class="px-4 py-1 text-xs rounded-2xl font-semibold text-red-500 bg-red-50">
                                            {{ __('messages.t_rejected') }}
                                        </span>
                                        @break
                                    @default
                                        
                                @endswitch
                            </td>
                            
                            {{-- Options --}}
                            <td class="p-3 text-center">
                                @if ($t->status === 'pending')
                                    
                                    {{-- Actions --}}
                                    <div class="space-x-4 rtl:space-x-reverse">

                                        {{-- Approve --}}
                                        <button type="button" wire:click="approve('{{ $t->id }}')" data-tooltip-target="tooltip-action-approve-{{ $t->id }}" class="inline-flex justify-center items-center border font-semibold focus:outline-none w-8 h-8 leading-5 text-sm rounded border-gray-300 bg-white text-gray-800 shadow-sm hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 hover:shadow focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-white active:border-white active:shadow-none">
                                            <svg class="w-4 h-4" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m10 15.586-3.293-3.293-1.414 1.414L10 18.414l9.707-9.707-1.414-1.414z"></path></svg>
                                        </button>
                                        <div id="tooltip-action-approve-{{ $t->id }}" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-xs font-medium text-white bg-gray-900 rounded-sm shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                            {{ __('messages.t_approve_payment') }}
                                        </div>
        
                                        {{-- Reject --}}
                                        <button type="button" id="modal-reject-payment-button-{{ $t->id }}" data-tooltip-target="tooltip-action-reject-{{ $t->id }}" class="inline-flex justify-center items-center border font-semibold focus:outline-none w-8 h-8 leading-5 text-sm rounded border-gray-300 bg-white text-gray-800 shadow-sm hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 hover:shadow focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-white active:border-white active:shadow-none">
                                            <svg class="w-4 h-4" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m16.192 6.344-4.243 4.242-4.242-4.242-1.414 1.414L10.535 12l-4.242 4.242 1.414 1.414 4.242-4.242 4.243 4.242 1.414-1.414L13.364 12l4.242-4.242z"></path></svg>
                                        </button>
                                        <div id="tooltip-action-reject-{{ $t->id }}" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-xs font-medium text-white bg-gray-900 rounded-sm shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                            {{ __('messages.t_reject_payment') }}
                                        </div>

                                    </div>

                                    {{-- Reject reason --}}
                                    <x-forms.modal id="modal-reject-payment-container-{{ $t->id }}" target="modal-reject-payment-button-{{ $t->id }}" uid="modal_{{ uid() }}" placement="center-center" size="max-w-md">

                                        {{-- Modal heading --}}
                                        <x-slot name="title">{{ __('messages.t_rejection_reason') }}</x-slot>

                                        {{-- Modal content --}}
                                        <x-slot name="content">

                                                {{-- Rejection reason --}}
                                                <div class="w-fill mb-6">
                                                    {{-- Form control --}}
                                                    <div class="relative w-full shadow-sm">
                                    
                                                        {{-- Input --}}
                                                        <textarea wire:model.defer="rejection_reason" id="bid-report-description-input" class="{{ $errors->first('rejection_reason') ? 'focus:ring-red-600 focus:border-red-600 border-red-500' : 'focus:ring-primary-600 focus:border-primary-600 border-gray-300' }} border text-gray-900 text-sm rounded-lg font-medium block w-full ltr:pr-12 rtl:pl-12 p-4  dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 placeholder:font-normal" rows="8" placeholder="{{ __('messages.t_enter_rejection_reason') }}"></textarea>
                                    
                                                        {{-- Icon --}}
                                                        <div class="absolute inset-y-0 ltr:right-0 rtl:left-0 flex items-center ltr:pr-3 rtl:pl-3 font-bold text-xs tracking-widest dark:text-gray-300 uppercase">
                                                            <svg class="w-5 h-5 text-gray-500" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M16 2H8C4.691 2 2 4.691 2 8v13a1 1 0 0 0 1 1h13c3.309 0 6-2.691 6-6V8c0-3.309-2.691-6-6-6zm4 14c0 2.206-1.794 4-4 4H4V8c0-2.206 1.794-4 4-4h8c2.206 0 4 1.794 4 4v8z"></path><path d="M11 6h2v8h-2zm0 10h2v2h-2z"></path></svg>
                                                        </div>
                                    
                                                    </div>
                                    
                                                    {{-- Error --}}
                                                    @error('rejection_reason')
                                                        <p class="mt-1.5 text-[13px] tracking-wide text-red-600 font-medium ltr:pl-1 rtl:pr-1">
                                                            {{ $errors->first('rejection_reason') }}
                                                        </p>
                                                    @enderror
                                                </div>

                                                {{-- Reject --}}
                                                <div>
                                                    <button 
                                                        wire:click="reject({{ $t->id }})"
                                                        wire:loading.class="border-gray-200 bg-gray-200 text-gray-700 cursor-not-allowed"
                                                        wire:loading.class.remove="border-red-200 bg-red-200 text-red-700 hover:text-red-700 hover:bg-red-300 hover:border-red-300 active:bg-red-200 focus:ring-red-500"
                                                        wire:loading.attr="disabled" 
                                                        type="button" 
                                                        class="w-full inline-flex text-xs justify-center items-center border font-semibold focus:outline-none px-3 py-2 leading-5 rounded border-red-200 bg-red-200 text-red-700 hover:text-red-700 hover:bg-red-300 hover:border-red-300 active:bg-red-200 focus:ring-red-500 focus:ring focus:ring-opacity-50">

                                                        {{-- Loading indicator --}}
                                                        <div wire:loading wire:target="reject({{ $t->id }})">
                                                            <svg role="status" class="inline w-4 h-4 text-gray-700 animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                                                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                                                            </svg>
                                                        </div>

                                                        {{-- Button text --}}
                                                        <div wire:loading.remove wire:target="reject({{ $t->id }})">
                                                            @lang('messages.t_reject_payment')
                                                        </div>

                                                    </button>
                                                </div>

                                        </x-slot>

                                    </x-forms.modal>

                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

    {{-- Pages --}}
    @if ($transactions->hasPages())
        <div class="px-4 py-5 sm:px-6 flex justify-center">
            {!! $transactions->links('pagination::tailwind') !!}
        </div>
    @endif

</div>