<div class="w-full relative">
    <div class="grid grid-cols-1 gap-y-5">

        {{-- Section head --}}
        <div class="w-full">
            <h4 class="text-base text-zinc-700 font-bold mb-1">@lang('messages.t_awarded_projects')</h4>
            <p class="leading-relaxed text-gray-500 text-sm">
                @lang('messages.t_awarded_projects_subtitle')
            </p>
        </div>

        {{-- Projects --}}
        @foreach ($projects as $p)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 px-4 sm:px-6 py-5 relative grid grid-cols-1 md:grid-cols-3 md:divide-x md:divide-gray-100 md:space-x-8 md:rtl:space-x-reverse" wire:key="awarded-projects-id-{{ $p->uid }}">

                {{-- Project & Bid Details --}}
                <div class="col-span-2">

                    {{-- Project title & status --}}
                    <div class="font-semibold text-base text-zinc-700 hover:text-primary-600 transition-colors duration-200 flex items-center space-x-3 rtl:space-x-reverse">

                        {{-- Title --}}
                        <a href="{{ url('project/' . $p->pid . '/' . $p->slug) }}" target="_blank">{{ $p->title }}</a>

                        {{-- Project status --}}
                        @if ($p->status === 'active' && !$p->awarded_bid->is_freelancer_accepted)
                            <div class="bg-amber-100 text-amber-600 text-xs font-medium tracking-wide px-3 py-1.5 rounded-sm inline">
                                @lang('messages.t_pending_ur_approval')
                            </div>
                        @endif

                    </div>

                    {{-- Project stats --}}
                    <div class="flex items-center mt-3 space-x-5 rtl:space-x-reverse text-xs text-gray-400">

                        {{-- Budget --}}
                        <div class="flex items-center space-x-1 rtl:space-x-reverse">
                            <svg class="w-4.5 h-4.5" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                            <div>
                                <span>@money($p->budget_min, settings('currency')->code, true)</span> / <span>@money($p->budget_max, settings('currency')->code, true)</span>    
                            </div>
                        </div>

                        {{-- Divider --}}
                        <div class="text-gray-200 text-base">|</div>

                        {{-- Budget type --}}
                        <div class="flex items-center space-x-1 rtl:space-x-reverse">
                            <svg class="w-4 h-4" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.496 2.132a1 1 0 00-.992 0l-7 4A1 1 0 003 8v7a1 1 0 100 2h14a1 1 0 100-2V8a1 1 0 00.496-1.868l-7-4zM6 9a1 1 0 00-1 1v3a1 1 0 102 0v-3a1 1 0 00-1-1zm3 1a1 1 0 012 0v3a1 1 0 11-2 0v-3zm5-1a1 1 0 00-1 1v3a1 1 0 102 0v-3a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            <div>
                                @if ($p->budget_type === 'fixed')
                                    @lang('messages.t_fixed_price')
                                @else
                                    @lang('messages.t_hourly_price')
                                @endif
                            </div>
                        </div>

                        {{-- Divider --}}
                        <div class="text-gray-200 text-base">|</div>

                        {{-- Posted date --}}
                        <div class="flex items-center space-x-1 rtl:space-x-reverse">
                            <svg class="w-4 h-4 -mt-0.5" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path></svg>
                            <span>{{ format_date($p->created_at, 'ago') }}</span>
                        </div>

                        {{-- Divider --}}
                        <div class="text-gray-200 text-base">|</div>

                        {{-- Views --}}
                        <div class="flex items-center space-x-1 rtl:space-x-reverse">
                            <svg class="w-4 h-4 -mt-0.5" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path></svg>
                            <span>{{ number_format($p->counter_views) }}</span>
                        </div>

                    </div>

                    {{-- Project description --}}
                    <div class="mt-3 leading-relaxed text-sm text-gray-600">
                        {{ add_3_dots($p->description, 155) }}
                    </div>

                    {{-- Divider --}}
                    <div class="h-px bg-gray-100 my-6 w-full"></div>

                    {{-- Your bid details --}}
                    <div class="w-full space-y-6">

                        {{-- Bid amount --}}
                        <div class="block">
                            <h3 class="font-semibold text-zinc-800 mb-2 text-sm">@lang('messages.t_bid_amount')</h3>
                            <p class="text-[13px] font-normal text-zinc-600 leading-relaxed">
                                @money($p->awarded_bid?->amount, settings('currency')->code, true)
                            </p>
                        </div>

                        {{-- Delivery date --}}
                        <div class="block">
                            <h3 class="font-semibold text-zinc-800 mb-2 text-sm">@lang('messages.t_delivery_time')</h3>
                            <p class="text-[13px] font-normal text-zinc-600 leading-relaxed">
                                {{ $p->awarded_bid?->days }} {{ strtolower(__('messages.t_days')) }}
                            </p>
                        </div>
                        
                        {{-- Bid description --}}
                        <div class="block">
                            <h3 class="font-semibold text-zinc-800 mb-2 text-sm">@lang('messages.t_proposal_message')</h3>
                            <p class="text-[13px] font-normal text-zinc-600 leading-relaxed">
                                {!! nl2br($p->awarded_bid?->message) !!}
                            </p>
                        </div>

                    </div>

                </div>

                {{-- Right side --}}
                <div class="flex items-center col-span-1 md:ltr:pl-8 md:rtl:pr-8">
                    <div class="space-y-4">

                        {{-- Client profile --}}
                        <div class="border shadow-sm rounded-lg flex grow flex-col items-center py-6 px-4 sm:px-5 mb-10">
    
                            {{-- Avatar --}}
                            <div class="relative">
                                <img class="rounded-full object-cover lazy h-16 w-16" src="{{ placeholder_img() }}" data-src="{{ src($p->client->avatar) }}" alt="{{ $p->client->username }}">
                            </div>
    
                            {{-- Fullname --}}
                            @if ($p->client->fullname)
                                <h3 class="pt-3 text-sm font-semibold text-zinc-700">
                                    {{ $p->client->fullname }}
                                </h3>
                            @endif
    
                            {{-- Username --}}
                            <div class="flex items-center space-x-4 rtl:space-x-reverse">
                                
                                {{-- Username --}}
                                <div class="flex items-center text-zinc-500 text-[13px] font-semibold">
    
                                    {{-- @ --}}
                                    <svg class="w-3.5 h-3.5" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10c1.466 0 2.961-.371 4.442-1.104l-.885-1.793C14.353 19.698 13.156 20 12 20c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8v1c0 .692-.313 2-1.5 2-1.396 0-1.494-1.819-1.5-2V8h-2v.025A4.954 4.954 0 0 0 12 7c-2.757 0-5 2.243-5 5s2.243 5 5 5c1.45 0 2.748-.631 3.662-1.621.524.89 1.408 1.621 2.838 1.621 2.273 0 3.5-2.061 3.5-4v-1c0-5.514-4.486-10-10-10zm0 13c-1.654 0-3-1.346-3-3s1.346-3 3-3 3 1.346 3 3-1.346 3-3 3z"></path></svg>
    
                                    {{-- Username --}}
                                    <span>{{ $p->client->username }}</span>
    
                                    {{-- Verified account --}}
                                    @if ($p->client->status === 'verified')
                                        <div>
                                            <img data-tooltip-target="tooltip-account-verified-{{ $p->client->uid }}" class="ltr:ml-0.5 rtl:mr-0.5 h-4 w-4 -mt-0.5" src="{{ url('public/img/auth/verified-badge.svg') }}" alt="{{ __('messages.t_account_verified') }}">
                                            <div id="tooltip-account-verified-{{ $p->client->uid }}" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-xs font-medium text-white bg-gray-900 rounded-sm shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                                {{ __('messages.t_account_verified') }}
                                            </div>
                                        </div>
                                    @endif
    
                                </div>

                            </div>
    
                            {{-- Actions --}}
                            <div class="flex items-center space-x-3 rtl:space-x-reverse mt-4">
    
                                {{-- Send a message --}}
                                <a href="{{ url('messages/new', $p->client->username) }}" target="_blank" class="flex items-center space-x-1 rtl:space-x-reverse hover:bg-gray-50 text-gray-500 hover:text-gray-600 border px-2 py-1.5 rounded bg-white shadow-sm transition-all ease-linear duration-200 text-xs font-medium">
                                    <svg class="w-4 h-4" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path></svg>
                                    <span>@lang('messages.t_send_a_message')</span>
                                </a>
    
                                {{-- Divider --}}
                                <span class="text-base text-gray-100">|</span>
    
                                {{-- View profile --}}
                                <a href="{{ url('profile', $p->client->username) }}" target="_blank" class="flex items-center space-x-1 rtl:space-x-reverse hover:bg-gray-50 text-gray-500 hover:text-gray-600 border px-2 py-1.5 rounded bg-white shadow-sm transition-all ease-linear duration-200 text-xs font-medium">
                                    <svg class="w-4 h-4" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"></path></svg>
                                    <span>@lang('messages.t_view_profile')</span>
                                </a>
    
                            </div>
                            
                        </div>
    
                        {{-- Accept / Reject the project --}}
                        @if ($p->awarded_bid && !$p->awarded_bid?->is_freelancer_accepted)

                            {{-- Actions --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 md:gap-x-3 gap-y-4 w-full">
    
                                {{-- Accept --}}
                                <button type="button" id="modal-accept-project-button-{{ $p->uid }}" class="inline-flex justify-center items-center font-semibold focus:outline-none h-10 leading-4 text-[13px] rounded bg-green-100 text-green-500 hover:bg-green-400 hover:text-white focus:ring focus:ring-green-500 focus:ring-opacity-25">
                                    @lang('messages.t_accept_the_project')
                                </button>

                                {{-- Accept confirm modal --}}
                                <x-forms.modal id="modal-accept-project-container-{{ $p->uid }}" target="modal-accept-project-button-{{ $p->uid }}" uid="modal_accept_uid_{{ $p->uid }}" placement="center-center" size="max-w-md">

                                    {{-- Modal heading --}}
                                    <x-slot name="title">{{ __('messages.t_confirmation') }}</x-slot>

                                    {{-- Modal content --}}
                                    <x-slot name="content">
                                        <p class="text-gray-500 font-normal text-sm tracking-wide leading-relaxed">
                                            @lang('messages.t_accept_project_confirmation_freelancer_msg')
                                        </p>
                                    </x-slot>

                                    {{-- Footer --}}
                                    <x-slot name="footer">
                                        <div class="flex justify-between items-center w-full">

                                            {{-- Cancel --}}
                                            <button x-on:click="close" type="button" class="inline-flex justify-center items-center space-x-2 rounded border font-semibold focus:outline-none px-3 py-2 leading-5 text-xs tracking-wide border-gray-300 bg-white text-gray-800 shadow-sm hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 hover:shadow focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-white active:border-white active:shadow-none">
                                                @lang('messages.t_cancel')
                                            </button>

                                            {{-- Accept --}}
                                            <button
                                                type="button" 
                                                wire:click="accept('{{ $p->uid }}')"
                                                wire:loading.attr="disabled"
                                                class="inline-flex justify-center items-center rounded border font-semibold focus:outline-none px-3 py-2 leading-5 text-xs tracking-wide border-transparent bg-primary-600 text-white hover:bg-primary-700 focus:ring focus:ring-primary-500 focus:ring-opacity-25 disabled:bg-gray-200 disabled:hover:bg-gray-200 disabled:text-gray-500 disabled:cursor-not-allowed">
                                                
                                                {{-- Loading indicator --}}
                                                <div wire:loading wire:target="accept('{{ $p->uid }}')">
                                                    <svg role="status" class="inline w-4 h-4 text-gray-700 animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                                                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                                                    </svg>
                                                </div>

                                                {{-- Button text --}}
                                                <div wire:loading.remove wire:target="accept('{{ $p->uid }}')">
                                                    @lang('messages.t_accept_the_project')
                                                </div>

                                            </button>

                                        </div>
                                    </x-slot>

                                </x-forms.modal>
    
                                {{-- Reject --}}
                                <button type="button" id="modal-reject-project-button-{{ $p->uid }}" class="inline-flex justify-center items-center font-semibold focus:outline-none h-10 leading-4 text-[13px] rounded bg-gray-100 text-gray-500 hover:bg-gray-200 hover:text-gray-800 focus:ring focus:ring-gray-500 focus:ring-opacity-25">
                                    @lang('messages.t_reject_the_project')
                                </button>

                                {{-- Reject confirm modal --}}
                                <x-forms.modal id="modal-reject-project-container-{{ $p->uid }}" target="modal-reject-project-button-{{ $p->uid }}" uid="modal_reject_uid_{{ $p->uid }}" placement="center-center" size="max-w-xl">

                                    {{-- Modal heading --}}
                                    <x-slot name="title">{{ __('messages.t_rejection_reason') }}</x-slot>

                                    {{-- Modal content --}}
                                    <x-slot name="content">
                                        <p class="text-gray-500 font-normal text-sm tracking-wide leading-relaxed">
                                            @lang('messages.t_pls_let_us_know_why_reject_this_project_freelancer')
                                        </p>
                                        <div class="grid grid-cols-12 md:gap-x-6 gap-y-6 py-2">

                                            {{-- Reason --}}
                                            <div class="col-span-12">
                                                <fieldset class="w-full">
                                                    <div class="space-y-4">
                                
                                                        {{-- List of reasons --}}
                                                        @for ($i = 1; $i <= 8; $i++)
                                                            <div class="flex items-center">
                                                                <input wire:model.defer="reject_reason" value="reason_{{ $i }}" id="freelancer_reject_project_reason_{{ $i }}" name="reject_reason" type="radio" class="focus:ring-primary-600 h-4 w-4 text-primary-700 border-gray-300" />
                                                                <label for="freelancer_reject_project_reason_{{ $i }}" class="ltr:ml-3 rtl:mr-3 block text-sm font-medium text-gray-700"> 
                                                                    {{ __('messages.t_freelancer_reject_project_reason_' . $i) }}  
                                                                </label>
                                                            </div>
                                                        @endfor
                                                        
                                                    </div>
                                                </fieldset>
                                            </div>

                                            {{-- Note --}}
                                            <div class="col-span-12">
                                                <div class="p-4 md:p-5 rounded text-orange-700 bg-orange-100">
                                                    <div class="flex items-center">
                                                        <svg class="inline-block w-5 h-5 ltr:mr-3 rtl:ml-3 flex-none text-orange-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                                                        <h3 class="font-semibold grow text-[13px]">
                                                            @lang('messages.t_ur_bid_will_hidden_after_reject')
                                                        </h3>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </x-slot>

                                    {{-- Footer --}}
                                    <x-slot name="footer">
                                        <div class="flex justify-between items-center w-full">

                                            {{-- Cancel --}}
                                            <button x-on:click="close" type="button" class="inline-flex justify-center items-center space-x-2 rounded border font-semibold focus:outline-none px-3 py-2 leading-5 text-xs tracking-wide border-gray-300 bg-white text-gray-800 shadow-sm hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 hover:shadow focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-white active:border-white active:shadow-none">
                                                @lang('messages.t_cancel')
                                            </button>

                                            {{-- Reject --}}
                                            <button
                                                type="button" 
                                                wire:click="reject('{{ $p->uid }}')"
                                                wire:loading.attr="disabled"
                                                class="inline-flex justify-center items-center rounded border font-semibold focus:outline-none px-3 py-2 leading-5 text-xs tracking-wide border-transparent bg-primary-600 text-white hover:bg-primary-700 focus:ring focus:ring-primary-500 focus:ring-opacity-25 disabled:bg-gray-200 disabled:hover:bg-gray-200 disabled:text-gray-500 disabled:cursor-not-allowed">
                                                
                                                {{-- Loading indicator --}}
                                                <div wire:loading wire:target="reject('{{ $p->uid }}')">
                                                    <svg role="status" class="inline w-4 h-4 text-gray-700 animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                                                        <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                                                    </svg>
                                                </div>

                                                {{-- Button text --}}
                                                <div wire:loading.remove wire:target="reject('{{ $p->uid }}')">
                                                    @lang('messages.t_reject_the_project')
                                                </div>

                                            </button>

                                        </div>
                                    </x-slot>

                                </x-forms.modal>
    
                            </div>
    
                            {{-- Info --}}
                            <div class="flex items-center space-x-2 rtl:space-x-reverse text-xs font-normal tracking-wide text-gray-400">
                                <svg class="h-10 w-10" stroke="currentColor" fill="currentColor" stroke-width="0" version="1.2" baseProfile="tiny" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M13.839 17.525c-.006.002-.559.186-1.039.186-.265 0-.372-.055-.406-.079-.168-.117-.48-.336.054-1.4l1-1.994c.593-1.184.681-2.329.245-3.225-.356-.733-1.039-1.236-1.92-1.416-.317-.065-.639-.097-.958-.097-1.849 0-3.094 1.08-3.146 1.126-.179.158-.221.42-.102.626.12.206.367.3.595.222.005-.002.559-.187 1.039-.187.263 0 .369.055.402.078.169.118.482.34-.051 1.402l-1 1.995c-.594 1.185-.681 2.33-.245 3.225.356.733 1.038 1.236 1.921 1.416.314.063.636.097.954.097 1.85 0 3.096-1.08 3.148-1.126.179-.157.221-.42.102-.626-.12-.205-.369-.297-.593-.223z"></path><circle cx="13" cy="6.001" r="2.5"></circle></svg>
                                <span>@lang('messages.t_u_have_36_hours_to_accept_project')</span>
                            </div>
    
                        @endif
    
                        {{-- Project details --}}
                        @if ($p->awarded_bid && $p->awarded_bid?->is_freelancer_accepted)
                            <a href="{{ url('account/projects/awarded/' . $p->pid . '/' . $p->slug) }}" class="w-full inline-flex justify-center items-center border font-semibold focus:outline-none h-10 leading-5 text-sm rounded border-gray-300 bg-white text-gray-800 shadow-sm hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 hover:shadow focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-white active:border-white active:shadow-none">
                                @lang('messages.t_project_overview')
                            </a>
                        @endif

                    </div>
                </div>


            </div>
        @endforeach

        {{-- Check if has no awarded projects --}}
        @if ($projects->count() === 0)
            <div class="p-16 flex flex-col items-center justify-center w-full border-2 border-dashed border-gray-200 rounded-lg">
                <div class="w-52 h-52 bg-gray-50 rounded-full flex items-center justify-center">
                    <svg class="w-40 h-40 text-zinc-400" xmlns="http://www.w3.org/2000/svg" id="currentIllo" viewBox="0 0 552.81023 554.00049" class="injected-svg DownloadModal__ImageFile-sc-p17csy-5 iIfSkb grid_media" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M353.50622,554.00049H113.05749c-26.06787,0-47.27588-21.20801-47.27588-47.27637V47.27588C65.78161,21.20801,86.98962,0,113.05749,0h240.44873c26.06738,0,47.27539,21.20801,47.27539,47.27588V506.72412c0,26.06836-21.20801,47.27637-47.27539,47.27637Z" fill="currentColor"></path><path d="M400.7818,169.00016c-1.10303,0-2,.89697-2,2v64c0,1.10303,.89697,2,2,2s2-.89697,2-2v-64c0-1.10303-.89697-2-2-2Z" fill="currentColor"></path><path d="M396.28204,47.27623V506.71623c0,22.69-17.65997,41.25-39.98999,42.69h-.01001c-.33002,.02002-.65997,.04004-1,.05005-.59003,.02997-1.17999,.03998-1.77997,.03998,0,0-2.20001-.16003-6.22003-.46002-4.06-.29999-9.96997-.75-17.34003-1.33997-2.20996-.17004-4.53998-.36005-7-.56-4.89996-.40002-10.27997-.84003-16.04999-1.34003-2.29999-.19-4.67999-.39996-7.10999-.60999-12.41998-1.07001-26.33002-2.32001-41-3.73999-2.47998-.23004-4.97998-.47003-7.5-.72003-.65997-.07001-1.33002-.13-2-.20001-10.46002-1.02997-21.21002-2.13995-32-3.31995-.66998-.07001-1.34003-.14001-2-.21002-18.59003-2.04999-37.25-4.28003-54.71002-6.67999-13.42999-1.84003-26.14996-3.78003-37.56995-5.79004-3.72003-.65997-7.30005-1.31995-10.72003-1.98999-.66998-.13-1.34003-.26001-2-.39996-24.16998-4.84003-40-10.06-40-15.42004V47.27623c0-23.62994,19.15002-42.77997,42.78003-42.77997h25.53998c4.08002,0,7.35999,3.17999,7.71002,7.25,.02997,.27002,.06,.53998,.10999,.81,.73999,4.09003,4.48999,6.94,8.64996,6.94h156.42004c4.15997,0,7.90997-2.84998,8.64996-6.94,.04999-.27002,.08002-.53998,.11005-.81,.34998-4.07001,3.62994-7.25,7.70996-7.25h25.54004c23.62994,0,42.77997,19.15002,42.77997,42.77997Z" fill="#fff"></path><path d="M64.7818,121.00016c-1.10303,0-2,.89697-2,2v16c0,1.10303,.89697,2,2,2s2-.89697,2-2v-16c0-1.10303-.89697-2-2-2Z" fill="currentColor"></path><path d="M64.7818,169.00016c-1.10303,0-2,.89697-2,2v31c0,1.10303,.89697,2,2,2s2-.89697,2-2v-31c0-1.10303-.89697-2-2-2Z" fill="currentColor"></path><path d="M64.7818,213.00016c-1.10303,0-2,.89697-2,2v31c0,1.10303,.89697,2,2,2s2-.89697,2-2v-31c0-1.10303-.89697-2-2-2Z" fill="currentColor"></path><rect x="99.2818" y="11.50016" width="39" height="5" rx="2" ry="2" fill="currentColor"></rect><circle cx="334.2818" cy="12.50016" r="4" fill="currentColor"></circle><circle cx="345.2818" cy="12.50016" r="4" fill="currentColor"></circle><circle cx="356.2818" cy="12.50016" r="4" fill="currentColor"></circle><ellipse cx="118.69075" cy="138.42544" rx="19.40894" ry="19" fill="currentColor"></ellipse><path d="M274.284,131.92544h-104.4707c-3.58398,0-6.5-2.91602-6.5-6.5s2.91602-6.5,6.5-6.5h104.4707c3.58398,0,6.5,2.91602,6.5,6.5s-2.91602,6.5-6.5,6.5Z" fill="#e6e6e6"></path><path d="M106.33723,351.87759c-1.66748,0-3.02393,1.35645-3.02393,3.02344,0,1.66797,1.35645,3.02441,3.02393,3.02441h109.42236c1.66748,0,3.02441-1.35645,3.02441-3.02441,0-1.66699-1.35693-3.02344-3.02441-3.02344H106.33723Z" fill="currentColor"></path><path d="M355.8133,153.92544H169.8133c-3.58398,0-6.5-2.91602-6.5-6.5s2.91602-6.5,6.5-6.5h186c3.58398,0,6.5,2.91602,6.5,6.5s-2.91602,6.5-6.5,6.5Z" fill="#e6e6e6"></path><path d="M143.53205,61.92544h-39.75049c-2.75684,0-5-2.24316-5-5s2.24316-5,5-5h39.75049c2.75684,0,4.99951,2.24316,4.99951,5s-2.24268,5-4.99951,5Z" fill="#e6e6e6"></path><rect x="311.0633" y="52.42544" width="48.75" height="9" rx="4.5" ry="4.5" fill="currentColor"></rect><path d="M298.3133,61.92544h-39.75c-2.75684,0-5-2.24316-5-5s2.24316-5,5-5h39.75c2.75684,0,5,2.24316,5,5s-2.24316,5-5,5Z" fill="#e6e6e6"></path><path d="M149.28156,337.19302h-38c-6.89258,0-12.5-5.60742-12.5-12.5v-12.41602c0-6.89258,5.60742-12.5,12.5-12.5h38c6.89258,0,12.5,5.60742,12.5,12.5v12.41602c0,6.89258-5.60742,12.5-12.5,12.5Z" fill="currentColor"></path><path d="M349.8133,265.63198H111.28156c-6.89258,0-12.5-5.60742-12.5-12.5v-54.70654c0-6.89258,5.60742-12.5,12.5-12.5h238.53174c6.89258,0,12.5,5.60742,12.5,12.5v54.70654c0,6.89258-5.60742,12.5-12.5,12.5Z" fill="currentColor"></path><path d="M348.28156,337.19302h-38c-6.89258,0-12.5-5.60742-12.5-12.5v-12.41602c0-6.89258,5.60742-12.5,12.5-12.5h38c6.89258,0,12.5,5.60742,12.5,12.5v12.41602c0,6.89258-5.60742,12.5-12.5,12.5Z" fill="currentColor"></path><path d="M247.8133,337.19302h-38c-6.89258,0-12.5-5.60742-12.5-12.5v-12.41602c0-6.89258,5.60742-12.5,12.5-12.5h38c6.89258,0,12.5,5.60742,12.5,12.5v12.41602c0,6.89258-5.60742,12.5-12.5,12.5Z" fill="currentColor"></path><g><rect x="132.8133" y="392.42544" width="30" height="28" fill="currentColor"></rect><rect x="145.8133" y="432.42544" width="17" height="28" fill="currentColor"></rect><rect x="171.8133" y="432.42544" width="30" height="28" fill="currentColor"></rect><rect x="171.8133" y="472.42544" width="30" height="28" fill="currentColor"></rect><rect x="210.8133" y="432.42544" width="30" height="28" fill="currentColor"></rect><rect x="171.8133" y="392.42544" width="30" height="28" fill="currentColor"></rect><rect x="210.8133" y="392.42544" width="30" height="28" fill="currentColor"></rect><rect x="288.8133" y="392.42544" width="30" height="28" fill="currentColor"></rect><rect x="210.8133" y="472.42544" width="30" height="28" fill="currentColor"></rect><rect x="249.8133" y="392.42544" width="30" height="28" fill="currentColor"></rect><path d="M334.3133,432.42544h-19.5v28h19.5c2.76141,0,5-2.23859,5-5v-18c0-2.76141-2.23859-5-5-5Z" fill="currentColor"></path><path d="M162.8133,472.42544h-6c-2.76141,0-5,2.23859-5,5v18c0,2.76141,2.23859,5,5,5h6v-28Z" fill="currentColor"></path><rect x="249.8133" y="472.42544" width="30" height="28" fill="currentColor"></rect><path d="M356.8133,392.42544h-29v28h29c2.76141,0,5-2.23859,5-5v-18c0-2.76141-2.23859-5-5-5Z" fill="currentColor"></path><path d="M305.8133,472.84756c-.61322-.26837-1.28778-.42212-2-.42212h-15v28h15c.71222,0,1.38678-.15375,2-.42212v-27.15576Z" fill="currentColor"></path><rect x="249.8133" y="432.42544" width="30" height="28" fill="currentColor"></rect><rect x="288.8133" y="432.42544" width="17" height="28" fill="currentColor"></rect><path d="M136.8133,432.42544h-10.5c-2.76141,0-5,2.23859-5,5v18c0,2.76141,2.23859,5,5,5h10.5v-28Z" fill="currentColor"></path><path d="M123.8133,392.42544h-20c-2.76141,0-5,2.23859-5,5v18c0,2.76141,2.23859,5,5,5h20v-28Z" fill="currentColor"></path></g><g><g><polygon points="316.82515 542.98033 307.91603 542.97945 303.67791 508.61532 316.82696 508.61623 316.82515 542.98033" fill="#ffb6b6"></polygon><path d="M317.13875,552.27431l-27.39684-.00104v-.34644c.00038-5.88914,4.77447-10.66312,10.66361-10.66345h.00066l5.00437-3.79656,9.33706,3.79716,2.39157,.00005-.00044,11.01027Z" fill="#2f2e41"></path></g><g><polygon points="372.29055 542.98033 363.38143 542.97945 359.14331 508.61532 372.29236 508.61623 372.29055 542.98033" fill="#ffb6b6"></polygon><path d="M372.60414,552.27431l-27.39684-.00104v-.34644c.00038-5.88914,4.77447-10.66312,10.66361-10.66345h.00066l5.00437-3.79656,9.33706,3.79716,2.39157,.00005-.00044,11.01027Z" fill="#2f2e41"></path></g><path d="M390.28538,279.82414h-76.51605l-9.28791,28.69357-2.24191,122.02388,.39306,95.2156,17.42574,.06546,15.34608-102.34871s-1.70717-10.06714,1.29968-8.66806-2.07495-10.95302,1.46595-9.77697,6.98602-46.59235,6.98602-46.59235l8.44355,72.10502,5.4228,95.2156h15.7152l13.17972-146.85976s-4.13888-8.8888,1.32736-14.79063,4.9058-54.66467,4.9058-54.66467l-3.86511-29.61799Z" fill="#2f2e41"></path><path d="M392.30165,278.01174s7.33624,10.44901,.93579,26.8176c-10.63721,27.2037-81.20551,4.32338-85.5265-12.62437-6.63904-2.29153,4.42834-5.64331,1.17065-11.20224-.19861-.33896,1.82935,7.20224,.22196-1.14214-2.58006-13.39376,1.84885-54.54097,1.84885-54.54097l1.46247-24.41914c1.41658-23.653,11.03304-46.07263,27.19463-63.40105l.00003-.00003h36.74401l15.94812,25.88385,1.65232,64.87546-1.65232,49.75303Z" fill="#3f3d56"></path><g><path d="M280.30018,280.48011l11.39312-17.27249,9.93564,5.73695-12.63999,17.79311c.03993,.68662-.00942,1.39093-.16186,2.09522-.82912,3.83053-4.34235,6.20118-7.84699,5.29496-3.50464-.90621-5.6736-4.74614-4.84448-8.57667,.52876-2.4429,2.15092-4.28699,4.16457-5.07108Z" fill="#ffb6b6"></path><path d="M334.22713,143.04594h-.00002c-12.76283,0-23.26451,10.04598-23.83033,22.79626l-2.4376,54.9289s-.01792,3.05049-1.14676,2.14705c-1.12884-.90344-1.22406,2.29177-1.22406,2.29177,0,0-.05125,4.88624-1.11582,2.08912-1.06457-2.79712-1.20943,2.26439-1.20943,2.26439l-15.50097,26.67106c2.0827,4.30719,.46436,9.63312-.05121,13.97049h0l11.14584,10.25906s-.09738-3.07992,2.30478-3.02801c14.72803,.31833,29.80719-39.46387,29.55365-54.1777-.28931-16.78925,3.51192-80.21237,3.51192-80.21237Z" fill="#3f3d56"></path></g><g><path d="M339.46327,66.6237l11.39312,17.27249,9.93564-5.73695-12.63999-17.79311c.03993-.68662-.00942-1.39093-.16186-2.09522-.82912-3.83053-4.34235-6.20118-7.84699-5.29496-3.50464,.90621-5.6736,4.74614-4.84448,8.57667,.52876,2.4429,2.15092,4.28699,4.16457,5.07108Z" fill="#ffb6b6"></path><path d="M393.39022,204.05788h-.00002c-12.76283,0-23.26451-10.04598-23.83033-22.79626l-2.4376-54.9289s-.01792-3.05049-1.14676-2.14705c-1.12884,.90344-1.22406-2.29177-1.22406-2.29177,0,0-.05125-4.88624-1.11582-2.08912-1.06457,2.79712-1.20943-2.26439-1.20943-2.26439l-20.27017-37.95131,1.15118-4.95047,11.09308-8.31981s4.48252,1.42696,5.92437,3.34899c7.27444,9.69705,29.28444,40.16627,29.55361,54.17772,.32253,16.78863,3.51195,80.21237,3.51195,80.21237Z" fill="#3f3d56"></path></g><path d="M348.40484,131.94776c.54344,.12537,1.08689,.23406,1.63028,.3177,1.68881,.27586,3.37762,.35949,5.02459,.25917,4.99948-.25917,9.71474-2.10683,13.52706-5.15001,4.0297-3.21035,7.03941-7.75002,8.30183-13.15083,2.96792-12.74955-4.96606-25.50748-17.71561-28.47539-12.5656-2.9345-25.13125,4.72363-28.3416,17.14711-.05016,.19227-.10032,.37623-.14216,.5685-2.96792,12.75792,4.95774,25.50748,17.71561,28.48376Z" fill="#ffb6b6"></path><path d="M378.12097,116.62591c-3.30939,8.73581-11.68467,15.65973-21.02138,16.07573-1.58998,.06472-3.24473-.05546-4.64982-.8135-1.41439-.74878-2.50521-2.2556-2.38503-3.84563,.24961-3.13375,4.51119-4.33555,5.71293-7.24745,.79499-1.92282-.12018-4.39101-1.98748-5.32469-1.86735-.92442-4.39101-.1849-5.45412,1.61774,1.94127-2.95817,.80424-7.12732-1.6455-9.66949-2.44043-2.55136-5.87007-3.85483-9.17952-5.09353l-.07391-1.25726c-2.33882,.89672-4.89024-.90592-5.90708-3.24473,1.18323-1.43284,1.93201-3.17077,2.65309-4.88093-.97063,.68406-2.05225,1.18323-3.19852,1.48831,.06472-2.03374-2.98587-3.00432-2.46819-5.00111,.72108-2.77327,4.89018-6.92392,3.52205-9.43838,2.55142,.72108,4.21537,3.69769,3.49435,6.24911,.91517-2.49595,1.83034-4.99191,2.74551-7.49706,.71182,1.73792,1.42359,3.47584,2.14467,5.20451,3.48509-2.17243,7.09031-4.38181,11.13929-5.07513,4.04898-.70251,8.70805,.48072,10.9729,3.9196,2.35727-1.4329,5.51884-.98914,7.84834,.49917,2.32956,1.48831,3.95656,3.84558,5.25997,6.28607,4.41877,8.22739,5.7869,18.32208,2.47745,27.04863Z" fill="#2f2e41"></path></g><g><path d="M498.55135,536.62631c2.06592,.12937,3.20768-2.43737,1.64468-3.93333l-.1555-.61819c.02047-.04951,.04105-.09897,.06178-.14839,2.08924-4.9818,9.16992-4.94742,11.24139,.04177,1.83859,4.42817,4.17942,8.86389,4.75579,13.54594,.25838,2.0668,.14213,4.17236-.31648,6.20047,4.30807-9.41059,6.57515-19.68661,6.57515-30.02077,0-2.59652-.14213-5.19301-.43275-7.78295-.239-2.11854-.56839-4.2241-.99471-6.31034-2.30575-11.2772-7.29852-22.01825-14.50012-30.98962-3.46197-1.89248-6.34906-4.85065-8.09295-8.39652-.62649-1.27891-1.11739-2.65462-1.34991-4.05618,.39398,.05168,1.48556-5.94866,1.18841-6.3168,.54906-.83317,1.53178-1.24733,2.13144-2.06034,2.98232-4.04341,7.0912-3.33741,9.23621,2.15727,4.58224,2.31266,4.62659,6.14806,1.81495,9.83683-1.78878,2.34682-2.03456,5.52233-3.60408,8.03478,.16151,.20671,.32944,.40695,.4909,.61366,2.96106,3.79788,5.52208,7.88002,7.68104,12.16859-.61017-4.76621,.29067-10.50822,1.82641-14.20959,1.74819-4.21732,5.02491-7.76915,7.91045-11.41501,3.46601-4.37924,10.57337-2.46806,11.18401,3.08332,.00591,.05375,.01166,.10745,.01731,.1612-.4286,.24178-.84849,.49867-1.25864,.76992-2.33949,1.54723-1.53096,5.17386,1.24107,5.60174l.06277,.00967c-.15503,1.54366-.41984,3.07444-.80734,4.57937,3.70179,14.31579-4.29011,19.5299-15.70147,19.76412-.25191,.12916-.49738,.25832-.74929,.38109,1.15617,3.25525,2.07982,6.59447,2.76441,9.97891,.61359,2.99043,1.03991,6.01317,1.27885,9.04888,.29715,3.83006,.27129,7.67959-.05168,11.50323l.01939-.13562c.82024-4.21115,3.10671-8.14462,6.4266-10.87028,4.94561-4.06264,11.93282-5.55869,17.26826-8.82425,2.56833-1.57196,5.85945,.45945,5.41121,3.43708l-.02182,.14261c-.79443,.32289-1.56947,.69755-2.31871,1.11733-.4286,.24184-.84848,.49867-1.25864,.76992-2.33949,1.54729-1.53096,5.17392,1.24107,5.6018l.06282,.00965c.0452,.00646,.08397,.01295,.12911,.01944-1.36282,3.23581-3.26168,6.23922-5.63854,8.82922-2.31463,12.49713-12.25603,13.68282-22.89022,10.04354h-.00648c-1.16259,5.06378-2.86128,10.01127-5.0444,14.72621h-18.02019c-.06463-.20022-.12274-.40692-.18089-.60717,1.6664,.10341,3.34571,.00649,4.98629-.29702-1.33701-1.64059-2.67396-3.29409-4.01097-4.93462-.03229-.0323-.05816-.0646-.08397-.09689-.67817-.8396-1.36282-1.67283-2.04099-2.51246l-.00036-.00102c-.04245-2.57755,.26652-5.14662,.87876-7.63984l.00057-.00035Z" fill="currentColor"></path><path d="M0,552.76927c0,.66003,.53003,1.19,1.19006,1.19H551.48004c.65997,0,1.19-.52997,1.19-1.19,0-.65997-.53003-1.19-1.19-1.19H1.19006c-.66003,0-1.19006,.53003-1.19006,1.19Z" fill="#ccc"></path></g></svg>
                </div>
                <h2 class="mt-5 block text-sm font-semibold text-zinc-800">@lang('messages.t_u_have_no_awarded_projects_yet')</h2>
                <a href="{{ url('explore/projects') }}" class="text-primary-600 text-[13px] hover:underline font-normal tracking-wide mt-1">@lang('messages.t_explore_projects')</a>
            </div>
        @endif

    </div>
</div>