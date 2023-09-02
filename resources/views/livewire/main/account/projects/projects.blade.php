<main class="w-full" x-data>
    <div class="px-4 sm:px-6 lg:px-8">

        {{-- Success message --}}
        @if (session()->has('success'))
            <div class="mb-6 w-full">
                <div class="rounded-sm bg-green-100 p-5">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/> </svg>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3">
                            <p class="text-sm font-medium text-green-800">{{ session()->get('success') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Error message --}}
        @if (session()->has('error'))
            <div class="mb-6 w-full">
                <div class="rounded-sm bg-red-100 p-5">
                    <div class="flex">
                        <div class="flex-shrink-0">
							<svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"> <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/> </svg>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3">
                            <p class="text-sm font-medium text-red-800">{{ session()->get('error') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Projects --}}
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow overflow-hidden">
            <div class="divide-y divide-gray-200 dark:divide-zinc-700">

                {{-- Form --}}
                <div class="w-full">

                    {{-- Section header --}}
                    <div class="mb-6 py-6 px-4 sm:p-4">
                        <h2 class="text-base leading-6 font-bold text-gray-900 dark:text-gray-100">{{ __('messages.t_my_projects') }}</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">{{ __('messages.t_my_projects_subtitle') }}</p>
                    </div>

                    {{-- Session message --}}
                    @if (session()->has('message'))
                        <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 mx-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11 14a1 1 0 11-2 0 1 1 0 012 0zm0-7a1 1 0 10-2 0v3a1 1 0 102 0V7z" clip-rule="evenodd"/></svg>
                                </div>
                                <div class="ltr:ml-3 rtl:mr-3 flex items-center">
                                    <p class="text-xs font-bold tracking-wide text-red-700">
                                        {{ session()->get('message') }}
                                    </p>
                                </div>
                            </div>
                        </div>  
                    @endif
                    
                    <div class="bg-white dark:bg-zinc-800 overflow-y-auto border !border-t-0 !border-b-0 dark:border-zinc-600">
                        <div class="grow w-full">
                            <div class="overflow-x-auto min-w-full bg-white">
                                <table class="min-w-full text-sm align-middle whitespace-nowrap">
                                    
                                    <thead>
                                        <tr>
                                            <th class="px-7 py-3 text-slate-600 bg-slate-100 font-bold text-[10px] uppercase tracking-widest ltr:text-left rtl:text-right max-w-xs">
                                                @lang('messages.t_project')
                                            </th>
                                            <th class="p-3 text-slate-600 bg-slate-100 font-bold text-[10px] uppercase tracking-widest text-center">
                                                @lang('messages.t_bids')
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
                                        @foreach ($projects as $project)
                                            <tr wire:key="projects-{{ $project->uid }}" class="{{ $loop->index % 2 == 0 ? 'bg-white' : 'bg-zinc-50' }}">
                    
                                                {{-- Project --}}
                                                <td class="px-7 py-3 ltr:text-left rtl:text-right max-w-xs">

                                                    {{-- Title --}}
                                                    <a href="{{ url('project/' . $project->pid . '/' . $project->slug) }}" class="text-black hover:text-primary-600 transition-colors duration-200 font-semibold text-sm overflow-hidden text-ellipsis block whitespace-nowrap max-w-xs">{{ $project->title }}</a>

                                                    {{-- Options --}}
                                                    <div class="mt-2 text-xs space-x-4 rtl:space-x-reverse flex items-center">
                                                        
                                                        {{-- Edit --}}
                                                        <a href="{{ url('account/projects/edit', $project->uid) }}" class="flex items-center space-x-1 rtl:space-x-reverse text-gray-500 group">
                                                            <svg class="w-4 h-4 text-gray-400 group-hover:text-zinc-600" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
                                                            <span class="group-hover:text-zinc-600 group-hover:underline">@lang('messages.t_edit')</span>
                                                        </a>

                                                        {{-- Details --}}
                                                        <div class="flex items-center space-x-1 rtl:space-x-reverse cursor-pointer" id="project-details-target-{{ $project->uid }}">
                                                            <svg class="w-4 h-4 text-gray-400" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path></svg>
                                                            <span>@lang('messages.t_details')</span>
                                                        </div>

                                                        {{-- Delete --}}
                                                        <button class="flex items-center space-x-1 rtl:space-x-reverse text-gray-500 group cursor-pointer disabled:cursor-not-allowed" wire:click="confirmDelete('{{ $project->uid }}')" wire:loading.attr="disabled">

                                                            {{-- Loading icon --}}
                                                            <div wire:loading wire:target="confirmDelete('{{ $project->uid }}')" wire:loading.block>
                                                                <div class="spinner is-grow flex relative h-4 w-4 ltr:mr-1 rtl:ml-1">
                                                                    <span class="absolute inline-block h-full w-full rounded-full bg-slate-500 opacity-75 dark:bg-navy-300"></span>
                                                                    <span class="absolute inline-block h-full w-full rounded-full bg-slate-500 opacity-75 dark:bg-navy-300"></span>
                                                                </div>
                                                            </div>

                                                            <svg wire:loading.remove wire:target="confirmDelete('{{ $project->uid }}')" class="w-4 h-4 text-gray-400 group-hover:text-red-600" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>

                                                            <span class="group-hover:text-red-600 group-hover:underline">@lang('messages.t_delete')</span>

                                                        </button>

                                                    </div>

                                                    {{-- Details modal --}}
                                                    <x-forms.modal id="project-details-container-{{ $project->uid }}" target="project-details-target-{{ $project->uid }}" uid="modal_details_{{ $project->uid }}" placement="center-center" size="max-w-4xl">
    
                                                        {{-- Header --}}
                                                        <x-slot name="title">{{ __('messages.t_project_details') }}</x-slot>
                                                
                                                        {{-- Content --}}
                                                        <x-slot name="content">
                                                            <div class="grid grid-cols-12 md:gap-x-6 gap-y-6 py-2">
                                                            </div>
                                                        </x-slot>
                                                
                                                    </x-forms.modal>

                                                </td>
                    
                                                {{-- Bids --}}
                                                <td class="p-3 text-center font-bold">
                                                    {{ $project->bids()->count() }}
                                                </td>

                                                {{-- Status --}}
                                                <td class="p-3 text-center font-bold">
                                                    @switch($project->status)

                                                        {{-- Active --}}
                                                        @case('active')
                                                            <div class="bg-emerald-100 text-emerald-600 text-xs font-medium tracking-wide px-3 py-1.5 rounded-sm inline">
                                                                @lang('messages.t_open')
                                                            </div>
                                                            @break

                                                        {{-- Pending approval --}}
                                                        @case('pending_approval')
                                                            <div class="bg-amber-100 text-amber-600 text-xs font-medium tracking-wide px-3 py-1.5 rounded-sm inline">
                                                                @lang('messages.t_pending_approval')
                                                            </div>
                                                            @break

                                                        {{-- Pending payment --}}
                                                        @case('pending_payment')
                                                            <div class="bg-yellow-100 text-yellow-600 text-xs font-medium tracking-wide px-3 py-1.5 rounded-sm inline">
                                                                @lang('messages.t_pending_payment')
                                                            </div>
                                                            @break

                                                        {{-- Closed --}}
                                                        @case('closed')
                                                            <div class="bg-gray-100 text-gray-600 text-xs font-medium tracking-wide px-3 py-1.5 rounded-sm inline">
                                                                @lang('messages.t_closed')
                                                            </div>
                                                            @break

                                                        {{-- Rejected --}}
                                                        @case('rejected')
                                                            <div class="bg-red-100 text-red-600 text-xs font-medium tracking-wide px-3 py-1.5 rounded-sm inline">
                                                                @lang('messages.t_rejected')
                                                            </div>
                                                            @break

                                                        {{-- Completed --}}
                                                        @case('completed')
                                                            <div class="bg-green-100 text-green-600 text-xs font-medium tracking-wide px-3 py-1.5 rounded-sm inline">
                                                                @lang('messages.t_completed')
                                                            </div>
                                                            @break

                                                        {{-- Under developement --}}
                                                        @case('under_development')
                                                            <div class="bg-blue-100 text-blue-600 text-xs font-medium tracking-wide px-3 py-1.5 rounded-sm inline">
                                                                @lang('messages.t_under_development')
                                                            </div>
                                                            @break

                                                        {{-- Hidden --}}
                                                        @case('hidden')
                                                            <div class="bg-zinc-100 text-zinc-600 text-xs font-medium tracking-wide px-3 py-1.5 rounded-sm inline">
                                                                @lang('messages.t_hidden')
                                                            </div>
                                                            @break

                                                        {{-- Incomplete --}}
                                                        @case('incomplete')
                                                            <div class="bg-rose-100 text-rose-600 text-xs font-medium tracking-wide px-3 py-1.5 rounded-sm inline">
                                                                @lang('messages.t_incomplete')
                                                            </div>
                                                            @break

                                                        {{-- Pending final review --}}
                                                        @case('pending_final_review')
                                                            <div class="bg-purple-100 text-purple-600 text-xs font-medium tracking-wide px-3 py-1.5 rounded-sm inline">
                                                                @lang('messages.t_pending_final_review')
                                                            </div>
                                                            @break

                                                        @case(2)
                                                            
                                                            @break
                                                        @default
                                                            
                                                    @endswitch
                                                </td>
                                                
                                                {{-- Options --}}
                                                <td class="p-3 text-center">
                                                    <div class="flex items-center justify-center space-x-4 rtl:space-x-reverse">

                                                        {{-- Pending payment --}}
                                                        @if ($project->status === 'pending_payment')
                                                            @php
                                                                $payment = \App\Models\ProjectSubscription::where('project_id', $project->id)->where('status', 'pending')->first();
                                                            @endphp
                                                            @if ($payment)
                                                                <div>
                                                                    <a href="{{ url('account/projects/checkout', $payment->uid) }}" data-tooltip-target="tooltip-actions-pay-{{ $project->uid }}" class="inline-flex justify-center items-center border font-semibold focus:outline-none w-8 h-8 leading-5 text-sm rounded border-gray-300 bg-white text-gray-800 shadow-sm hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 hover:shadow focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-white active:border-white active:shadow-none">
                                                                        <svg class="w-4 h-4" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g><path fill="none" d="M0 0h24v24H0z"></path><path d="M6.5 2h11a1 1 0 0 1 .8.4L21 6v15a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6l2.7-3.6a1 1 0 0 1 .8-.4zm12 4L17 4H7L5.5 6h13zM9 10H7v2a5 5 0 0 0 10 0v-2h-2v2a3 3 0 0 1-6 0v-2z"></path></g></svg>
                                                                    </a>
                                                                    <x-forms.tooltip id="tooltip-actions-pay-{{ $project->uid }}" :text="__('messages.t_pay')" />
                                                                </div>
                                                            @endif
                                                        @endif

                                                        {{-- Bids --}}
                                                        <div>
                                                            <a href="{{ url('project/' . $project->pid . '/' . $project->slug) }}" data-tooltip-target="tooltip-actions-bids-{{ $project->uid }}" class="inline-flex justify-center items-center border font-semibold focus:outline-none w-8 h-8 leading-5 text-sm rounded border-gray-300 bg-white text-gray-800 shadow-sm hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 hover:shadow focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-white active:border-white active:shadow-none">
                                                                <svg class="w-4 h-4" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g><path fill="none" d="M0 0h24v24H0z"></path><path d="M14 20v2H2v-2h12zM14.586.686l7.778 7.778L20.95 9.88l-1.06-.354L17.413 12l5.657 5.657-1.414 1.414L16 13.414l-2.404 2.404.283 1.132-1.415 1.414-7.778-7.778 1.415-1.414 1.13.282 6.294-6.293-.353-1.06L14.586.686z"></path></g></svg>
                                                            </a>
                                                            <x-forms.tooltip id="tooltip-actions-bids-{{ $project->uid }}" :text="__('messages.t_bids')" />
                                                        </div>

                                                        {{-- Overview --}}
                                                        <div>
                                                            <a href="{{ url('account/projects/overview', $project->uid) }}" data-tooltip-target="tooltip-actions-overview-{{ $project->uid }}" class="inline-flex justify-center items-center border font-semibold focus:outline-none w-8 h-8 leading-5 text-sm rounded border-gray-300 bg-white text-gray-800 shadow-sm hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 hover:shadow focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-white active:border-white active:shadow-none">
                                                                <svg class="w-4 h-4" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><g><path fill="none" d="M0 0H24V24H0z"></path><path d="M15 3c.552 0 1 .448 1 1v2h5c.552 0 1 .448 1 1v13c0 .552-.448 1-1 1H3c-.552 0-1-.448-1-1V7c0-.552.448-1 1-1h5V4c0-.552.448-1 1-1h6zM8 8H6v11h2V8zm10 0h-2v11h2V8zm-4-3h-4v1h4V5z"></path></g></svg>
                                                            </a>
                                                            <x-forms.tooltip id="tooltip-actions-overview-{{ $project->uid }}" :text="__('messages.t_overview')" />
                                                        </div>
                    
                                                    </div>
                    
                                                </td>
                    
                                            </tr>
                                        @endforeach
                                    </tbody>
                    
                                </table>
                            </div>
                        </div>
                    </div>
                
                    {{-- Pagination --}}
                    @if ($projects->hasPages())
                        <div class="bg-gray-100 px-4 py-5 sm:px-6 rounded-bl-lg rounded-br-lg flex justify-center border-t-0 border-r border-l border-b">
                            {!! $projects->links('pagination::tailwind') !!}
                        </div>
                    @endif

                </div>

            </div>
        </div>
        
    </div>
</main>