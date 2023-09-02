<div class="rounded-lg shadow bg-white overflow-hidden inline-grid w-full">
   
    {{-- Section header --}}
    <div class="py-4 px-5 lg:px-6 w-full sm:flex sm:justify-between sm:items-center">
        <div class="flex justify-center sm:justify-left">
            <h3 class="inline-flex items-center font-semibold">
                <span>@lang('messages.t_projects')</span>
            </h3>
        </div>
    </div>
    
    {{-- Section content --}}
    <div class="grow w-full contents">
        <div class="overflow-x-auto min-w-full bg-white">
            <table class="min-w-full text-sm align-middle whitespace-nowrap">
                
                <thead>
                    <tr>
                        <th class="px-7 py-3 text-slate-600 bg-slate-100 font-bold text-[10px] uppercase tracking-widest ltr:text-left rtl:text-right">
                            @lang('messages.t_project')
                        </th>
                        <th class="p-3 text-slate-600 bg-slate-100 font-bold text-[10px] uppercase tracking-widest text-center">
                            @lang('messages.t_bids')
                        </th>
                        <th class="p-3 text-slate-600 bg-slate-100 font-bold text-[10px] uppercase tracking-widest text-center">
                            @lang('messages.t_milestones')
                        </th>
                        <th class="p-3 text-slate-600 bg-slate-100 font-bold text-[10px] uppercase tracking-widest text-center">
                            @lang('messages.t_status')
                        </th>
                        <th class="p-3 text-slate-600 bg-slate-100 font-bold text-[10px] uppercase tracking-widest text-center">
                            @lang('messages.t_posted_date')
                        </th>
                        <th class="p-3 text-slate-600 bg-slate-100 font-bold text-[10px] uppercase tracking-widest text-center">
                            @lang('messages.t_options')
                        </th>
                    </tr>
                </thead>
            
                <tbody class="divide-y divide-gray-100">
                    @foreach ($projects as $p)
                        <tr class="hover:bg-gray-50" wire:key="projects-{{ $p->uid }}">

                            {{-- Project --}}
                            <td class="px-7 py-4 ltr:text-left rtl:text-right max-w-xs">

                                {{-- Title --}}
                                <a href="{{ url('project/' . $p->pid . '/' . $p->slug) }}" target="_blank" class="text-zinc-900 hover:text-primary-600 transition-colors duration-500 font-semibold text-sm block max-w-xs truncate">{{ $p->title }}</a>

                                {{-- Details --}}
                                <div class="flex items-center space-x-3 rtl:space-x-reverse mt-2">
                                    
                                    {{-- Employer --}}
                                    <a href="{{ url('profile', $p->client->username) }}" target="_blank" class="flex items-center space-x-2 rtl:space-x-reverse text-gray-500 hover:text-primary-600 text-xs">
                                        <img class="w-3.5 h-3.5 rounded-full object-cover lazy" src="{{ placeholder_img() }}" data-src="{{ src($p->client->avatar) }}" alt="{{ $p->client->username }}" />
                                        <div>{{ $p->client->username }}</div>
                                    </a>
            
                                    <div class="mx-2 my-0.5 text-gray-300">|</div>

                                    {{-- Budget --}}
                                    <div class="flex items-center space-x-2 rtl:space-x-reverse text-gray-500 text-xs">
                                        <svg class="h-3.5 w-3.5 text-zinc-400" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g><path fill="none" d="M0 0h24v24H0z"></path><path d="M22 6h-7a6 6 0 1 0 0 12h7v2a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h18a1 1 0 0 1 1 1v2zm-7 2h8v8h-8a4 4 0 1 1 0-8zm0 3v2h3v-2h-3z"></path></g></svg>
                                        <div>
                                            {{-- Min price --}}
                                            <span>@money($p->budget_min, settings('currency')->code, true)</span>
                    
                                            {{-- Divider --}}
                                            <span class="text-xs text-gray-400 px-px">/</span>
                    
                                            {{-- Max price --}}
                                            <span>@money($p->budget_max, settings('currency')->code, true)</span>
                                        </div>
                                    </div>

                                </div>

                            </td>

                            {{-- Bids --}}
                            <td class="p-4 text-center font-semibold">
                                {{ $p->bids_count }}
                            </td>

                            {{-- Milestones --}}
                            <td class="p-4 text-center font-semibold">
                                {{ $p->milestones_count }}
                            </td>

                            {{-- Status --}}
                            <td class="p-4 text-center">
                                @switch($p->status)

                                    {{-- Active --}}
                                    @case('active')
                                        <span class="bg-emerald-100 text-emerald-700 px-4 inline-block leading-9 h-9 rounded-3xl font-medium text-xs">
                                            @lang('messages.t_active')
                                        </span>
                                        @break

                                    {{-- Pending approval --}}
                                    @case('pending_approval')
                                        <div class="flex items-center flex-col">
                                            <div class="bg-yellow-100 text-yellow-600 px-4 leading-9 h-9 rounded-3xl font-medium text-xs flex items-center space-x-3 rtl:space-x-reverse">
                                                <span>@lang('messages.t_pending_approval')</span>
                                                <div class="flex items-center space-x-1 rtl:space-x-reverse">

                                                    {{-- Approve --}}
                                                    <div>

                                                        {{-- Button --}}
                                                        <button type="button" data-tooltip-target="tooltip-actions-approve-{{ $p->uid }}" id="modal-approve-project-button-{{ $p->uid }}" class="flex items-center justify-center h-6 w-6 rounded-full border border-transparent bg-white text-yellow-600 hover:bg-transparent hover:border-yellow-600">
                                                            <svg class="w-4 h-4" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g><path fill="none" d="M0 0h24v24H0z"></path><path d="M10 15.172l9.192-9.193 1.415 1.414L10 18l-6.364-6.364 1.414-1.414z"></path></g></svg>
                                                        </button>

                                                        {{-- Tooltip --}}
                                                        <x-forms.tooltip id="tooltip-actions-approve-{{ $p->uid }}" :text="__('messages.t_approve')" />

                                                    </div>

                                                    {{-- Reject --}}
                                                    <div>

                                                        {{-- Button --}}
                                                        <button type="button" data-tooltip-target="tooltip-actions-reject-{{ $p->uid }}" id="modal-reject-project-button-{{ $p->uid }}" class="flex items-center justify-center h-6 w-6 rounded-full border border-transparent bg-white text-yellow-600 hover:bg-transparent hover:border-yellow-600">
                                                            <svg class="w-4 h-4" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g><path fill="none" d="M0 0h24v24H0z"></path><path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"></path></g></svg>
                                                        </button>

                                                        {{-- Tooltip --}}
                                                        <x-forms.tooltip id="tooltip-actions-reject-{{ $p->uid }}" :text="__('messages.t_reject')" />

                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        @break

                                    {{-- Pending payment --}}
                                    @case('pending_payment')
                                        <span class="bg-amber-100 text-amber-600 px-4 inline-block leading-9 h-9 rounded-3xl font-medium text-xs">
                                            @lang('messages.t_pending_payment')
                                        </span>
                                        @break

                                    {{-- Completed --}}
                                    @case('completed')
                                        <span class="bg-green-100 text-green-600 px-4 inline-block leading-9 h-9 rounded-3xl font-medium text-xs">
                                            @lang('messages.t_completed')
                                        </span>
                                        @break

                                    {{-- Hidden --}}
                                    @case('hidden')
                                        <span class="bg-gray-100 text-gray-600 px-4 inline-block leading-9 h-9 rounded-3xl font-medium text-xs">
                                            @lang('messages.t_hidden')
                                        </span>
                                        @break

                                    {{-- Rejected --}}
                                    @case('rejected')
                                        <span class="bg-red-100 text-red-600 px-4 inline-block leading-9 h-9 rounded-3xl font-medium text-xs">
                                            @lang('messages.t_rejected')
                                        </span>
                                        @break

                                    {{-- Under development --}}
                                    @case('under_development')
                                        <span class="bg-blue-100 text-blue-600 px-4 inline-block leading-9 h-9 rounded-3xl font-medium text-xs">
                                            @lang('messages.t_under_development')
                                        </span>
                                        @break

                                    {{-- Pending final review --}}
                                    @case('pending_final_review')
                                        <span class="bg-fuchsia-100 text-fuchsia-600 px-4 inline-block leading-9 h-9 rounded-3xl font-medium text-xs">
                                            @lang('messages.t_pending_final_review')
                                        </span>
                                        @break

                                    {{-- Incomplete --}}
                                    @case('incomplete')
                                        <span class="bg-stone-100 text-stone-600 px-4 inline-block leading-9 h-9 rounded-3xl font-medium text-xs">
                                            @lang('messages.t_incomplete')
                                        </span>
                                        @break

                                    {{-- Closed --}}
                                    @case('closed')
                                        <span class="bg-rose-100 text-rose-600 px-4 inline-block leading-9 h-9 rounded-3xl font-medium text-xs">
                                            @lang('messages.t_closed')
                                        </span>
                                        @break

                                    @default
                                        
                                @endswitch
                            </td>

                            {{-- Date --}}
                            <td class="p-4 text-center text-gray-500 text-xs">
                                {{ format_date($p->created_at) }}
                            </td>

                            {{-- Options --}}
                            <td class="p-4 text-center">
                                <div class="flex items-center justify-center space-x-4 rtl:space-x-reverse">

                                    {{-- View --}}
                                    <div>
                                        <a href="{{ url('project/' . $p->pid . '/' . $p->slug) }}" target="_blank" data-tooltip-target="tooltip-actions-view-{{ $p->uid }}" class="inline-flex justify-center items-center border font-semibold focus:outline-none w-8 h-8 leading-5 text-sm rounded border-gray-300 bg-white text-gray-800 shadow-sm hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 hover:shadow focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-white active:border-white active:shadow-none">
                                            <svg class="w-4 h-4" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g><path fill="none" d="M0 0h24v24H0z"></path><path d="M1.181 12C2.121 6.88 6.608 3 12 3c5.392 0 9.878 3.88 10.819 9-.94 5.12-5.427 9-10.819 9-5.392 0-9.878-3.88-10.819-9zM12 17a5 5 0 1 0 0-10 5 5 0 0 0 0 10zm0-2a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"></path></g></svg>
                                        </a>
                                        <x-forms.tooltip id="tooltip-actions-view-{{ $p->uid }}" :text="__('messages.t_view_project')" />
                                    </div>

                                    {{-- Milestones --}}
                                    <div>
                                        <button type="button" data-tooltip-target="tooltip-actions-milestones-{{ $p->uid }}" id="modal-milestones-project-button-{{ $p->uid }}" class="inline-flex justify-center items-center border font-semibold focus:outline-none w-8 h-8 leading-5 text-sm rounded border-gray-300 bg-white text-gray-800 shadow-sm hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 hover:shadow focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-white active:border-white active:shadow-none">
                                            <svg class="w-4 h-4" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g><path fill="none" d="M0 0h24v24H0z"></path><path d="M20.083 10.5l1.202.721a.5.5 0 0 1 0 .858L12 17.65l-9.285-5.571a.5.5 0 0 1 0-.858l1.202-.721L12 15.35l8.083-4.85zm0 4.7l1.202.721a.5.5 0 0 1 0 .858l-8.77 5.262a1 1 0 0 1-1.03 0l-8.77-5.262a.5.5 0 0 1 0-.858l1.202-.721L12 20.05l8.083-4.85zM12.514 1.309l8.771 5.262a.5.5 0 0 1 0 .858L12 13 2.715 7.429a.5.5 0 0 1 0-.858l8.77-5.262a1 1 0 0 1 1.03 0z"></path></g></svg>
                                        </button>
                                        <x-forms.tooltip id="tooltip-actions-milestones-{{ $p->uid }}" :text="__('messages.t_milestones')" />
                                    </div>

                                    {{-- Delete --}}
                                    <div>
                                        <button type="button" data-tooltip-target="tooltip-actions-delete-{{ $p->uid }}" id="modal-delete-project-button-{{ $p->uid }}" class="inline-flex justify-center items-center border font-semibold focus:outline-none w-8 h-8 leading-5 text-sm rounded border-gray-300 bg-white text-gray-800 shadow-sm hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 hover:shadow focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-white active:border-white active:shadow-none">
                                            <svg class="w-4 h-4" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g><path fill="none" d="M0 0h24v24H0z"></path><path d="M17 4h5v2h-2v15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V6H2V4h5V2h10v2zM9 9v8h2V9H9zm4 0v8h2V9h-2z"></path></g></svg>
                                        </button>
                                        <x-forms.tooltip id="tooltip-actions-delete-{{ $p->uid }}" :text="__('messages.t_delete_project')" />
                                    </div>

                                </div>

                            </td>

                        </tr>

                        {{-- Approve modal --}}
                        <x-forms.modal id="modal-approve-project-container-{{ $p->uid }}" target="modal-approve-project-button-{{ $p->uid }}" uid="modal_approve_project_{{ $p->uid }}" placement="center-center" size="max-w-md">

                            {{-- Modal heading --}}
                            <x-slot name="title">{{ __('messages.t_approve_project') }}</x-slot>

                            {{-- Modal content --}}
                            <x-slot name="content">
                                <div class="flex text-gray-500 font-normal text-sm">@lang('messages.t_are_u_sure_approve_project_admin')</div>
                                <a href="{{ url('project/' . $p->pid . '/' . $p->slug) }}" target="_blank" class="text-zinc-700 hover:text-primary-600 font-semibold text-[13px] block">{{ $p->title }}</a>
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
                                        wire:click="approve('{{ $p->id }}')"
                                        wire:loading.attr="disabled"
                                        class="inline-flex justify-center items-center rounded border font-semibold focus:outline-none px-3 py-2 leading-5 text-xs tracking-wide border-transparent bg-green-500 text-white hover:bg-green-600 focus:ring focus:ring-green-500 focus:ring-opacity-25 disabled:bg-gray-200 disabled:hover:bg-gray-200 disabled:text-gray-500 disabled:cursor-not-allowed">
                                        
                                        {{-- Loading indicator --}}
                                        <div wire:loading wire:target="approve('{{ $p->id }}')">
                                            <svg role="status" class="inline w-4 h-4 text-gray-700 animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                                            </svg>
                                        </div>

                                        {{-- Button text --}}
                                        <div wire:loading.remove wire:target="approve('{{ $p->id }}')">
                                            @lang('messages.t_approve_project')
                                        </div>

                                    </button>

                                </div>
                            </x-slot>

                        </x-forms.modal>

                        {{-- Reject modal --}}
                        <x-forms.modal id="modal-reject-project-container-{{ $p->uid }}" target="modal-reject-project-button-{{ $p->uid }}" uid="modal_reject_project_{{ $p->uid }}" placement="center-center" size="max-w-md">

                            {{-- Modal heading --}}
                            <x-slot name="title">{{ __('messages.t_reject_project') }}</x-slot>

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
                                        wire:click="reject('{{ $p->id }}')"
                                        wire:loading.attr="disabled"
                                        class="inline-flex justify-center items-center rounded border font-semibold focus:outline-none px-3 py-2 leading-5 text-xs tracking-wide border-transparent bg-red-500 text-white hover:bg-red-600 focus:ring focus:ring-red-500 focus:ring-opacity-25 disabled:bg-gray-200 disabled:hover:bg-gray-200 disabled:text-gray-500 disabled:cursor-not-allowed">
                                        
                                        {{-- Loading indicator --}}
                                        <div wire:loading wire:target="reject('{{ $p->id }}')">
                                            <svg role="status" class="inline w-4 h-4 text-gray-700 animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                                            </svg>
                                        </div>

                                        {{-- Button text --}}
                                        <div wire:loading.remove wire:target="reject('{{ $p->id }}')">
                                            @lang('messages.t_reject_project')
                                        </div>

                                    </button>

                                </div>
                            </x-slot>

                        </x-forms.modal>

                        {{-- Delete modal --}}
                        <x-forms.modal id="modal-delete-project-container-{{ $p->uid }}" target="modal-delete-project-button-{{ $p->uid }}" uid="modal_delete_project_{{ $p->uid }}" placement="center-center" size="max-w-md">

                            {{-- Modal heading --}}
                            <x-slot name="title">{{ __('messages.t_delete_project') }}</x-slot>

                            {{-- Modal content --}}
                            <x-slot name="content">
                                <div class="flex flex-col items-center">

                                    {{-- Alert icon --}}
                                    <div class="h-16 w-16 flex items-center justify-center bg-red-100 rounded-full">
                                        <svg class="w-7 h-7 text-red-400 -mt-1" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g><path fill="none" d="M0 0h24v24H0z"></path><path d="M12.866 3l9.526 16.5a1 1 0 0 1-.866 1.5H2.474a1 1 0 0 1-.866-1.5L11.134 3a1 1 0 0 1 1.732 0zM11 16v2h2v-2h-2zm0-7v5h2V9h-2z"></path></g></svg>
                                    </div>
    
                                    {{-- Attention --}}
                                    <div class="mt-1.5 font-semibold text-sm text-red-500">
                                        @lang('messages.t_attention_needed')
                                    </div>

                                    {{-- Alert message --}}
                                    <div class="text-center leading-relaxed text-[13px] mt-4 text-gray-500">
                                        {!! nl2br(__('messages.t_delete_project_admin_alert')) !!}
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

                                    {{-- Delete --}}
                                    <button
                                        type="button" 
                                        wire:click="delete('{{ $p->id }}')"
                                        wire:loading.attr="disabled"
                                        class="inline-flex justify-center items-center rounded border font-semibold focus:outline-none px-3 py-2 leading-5 text-xs tracking-wide border-transparent bg-red-500 text-white hover:bg-red-600 focus:ring focus:ring-red-500 focus:ring-opacity-25 disabled:bg-gray-200 disabled:hover:bg-gray-200 disabled:text-gray-500 disabled:cursor-not-allowed">
                                        
                                        {{-- Loading indicator --}}
                                        <div wire:loading wire:target="delete('{{ $p->id }}')">
                                            <svg role="status" class="inline w-4 h-4 text-gray-700 animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                                            </svg>
                                        </div>

                                        {{-- Button text --}}
                                        <div wire:loading.remove wire:target="delete('{{ $p->id }}')">
                                            @lang('messages.t_delete_project')
                                        </div>

                                    </button>

                                </div>
                            </x-slot>

                        </x-forms.modal>

                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

    {{-- Pages --}}
    @if ($projects->hasPages())
        <div class="px-4 py-5 sm:px-6 flex justify-center">
            {!! $projects->links('pagination::tailwind') !!}
        </div>
    @endif

</div>