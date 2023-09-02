<div class="min-h-full bg-gray-100 rounded-lg shadow-sm" x-data="window.ltbiSPVMBMrZWHY" x-cloak>
    <div class="min-h-full">

        {{-- Header --}}
        <header class="pb-24 bg-primary-600 rounded-t-lg" x-data="Components.popover({ open: false, focus: true })" x-init="init()" @keydown.escape="onEscape" @close-popover-group.window="onClosePopoverGroup">

            {{-- Desktop menu --}}
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <div class="hidden lg:block border-t border-white border-opacity-20 py-5">
                    <div class="grid grid-cols-3 gap-8 items-center">
                        <div class="col-span-2">
                            <nav class="flex space-x-4 rtl:space-x-reverse">
                            
                                {{-- Overview --}}
                                <button type="button" x-on:click="selectedTab = 1" class="text-sm font-medium rounded-md bg-white bg-opacity-0 px-3 py-2 hover:bg-opacity-10" :class="selectedTab === 1 ? 'text-white' : 'text-primary-100/60'">
                                    @lang('messages.t_overview')
                                </button>

                                {{-- Milestones --}}
                                <button type="button" x-on:click="selectedTab = 2" class="text-sm font-medium rounded-md bg-white bg-opacity-0 px-3 py-2 hover:bg-opacity-10" :class="selectedTab === 2 ? 'text-white' : 'text-primary-100/60'">
                                    @lang('messages.t_milestones')
                                </button>
                            
                                {{-- Shared files --}}
                                <button type="button" x-on:click="selectedTab = 3" class="text-sm font-medium rounded-md bg-white bg-opacity-0 px-3 py-2 hover:bg-opacity-10" :class="selectedTab === 3 ? 'text-white' : 'text-primary-100/60'">
                                    @lang('messages.t_shared_files')
                                </button>
                            
                            </nav>
                        </div>
                        <div>
                            <div class="max-w-md w-full mx-auto">
                            <label for="mobile-search" class="sr-only">Search</label>
                            <div class="relative text-white focus-within:text-gray-600">
                                <div class="pointer-events-none absolute inset-y-0 left-0 pl-3 flex items-center">
                                    <svg class="h-5 w-5" x-description="Heroicon name: solid/search" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <input id="mobile-search" class="block w-full bg-white bg-opacity-20 py-2 pl-10 pr-3 border border-transparent rounded-md leading-5 text-gray-900 placeholder-white focus:outline-none focus:bg-opacity-100 focus:border-transparent focus:placeholder-gray-500 focus:ring-0 sm:text-sm" placeholder="Search" type="search" name="search">
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            {{-- Mobile menu --}}
            <div class="lg:hidden">
                
                {{-- Backdrop --}}
                <div 
                    x-show="open" 
                    x-transition:enter="duration-150 ease-out" 
                    x-transition:enter-start="opacity-0" 
                    x-transition:enter-end="opacity-100" 
                    x-transition:leave="duration-150 ease-in" 
                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                    class="z-20 fixed inset-0 bg-black bg-opacity-25" 
                    @click="toggle" aria-hidden="true">
                </div>
                
                {{-- Menu --}}
                <div 
                    x-show="open" 
                    x-transition:enter="duration-150 ease-out" 
                    x-transition:enter-start="opacity-0 scale-95" 
                    x-transition:enter-end="opacity-100 scale-100" 
                    x-transition:leave="duration-150 ease-in" 
                    x-transition:leave-start="opacity-100 scale-100" 
                    x-transition:leave-end="opacity-0 scale-95" 
                    class="z-30 absolute top-0 inset-x-0 max-w-3xl mx-auto w-full p-2 transition transform origin-top" 
                    x-ref="panel" @click.away="open = false">

                    <div class="rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 bg-white divide-y divide-gray-200">
                    <div class="pt-3 pb-2">
                        <div class="flex items-center justify-between px-4">
                        <div>
                            <img class="h-8 w-auto" src="https://tailwindui.com/img/logos/workflow-mark-indigo-600.svg" alt="Workflow">
                        </div>
                        <div class="-mr-2">
                            <button type="button" class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500" @click="toggle">
                            <span class="sr-only">Close menu</span>
                            <svg class="h-6 w-6" x-description="Heroicon name: outline/x" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            </button>
                        </div>
                        </div>
                        <div class="mt-3 px-2 space-y-1">
                        <a href="#" class="block rounded-md px-3 py-2 text-base text-gray-900 font-medium hover:bg-gray-100 hover:text-gray-800">Home</a>
                        <a href="#" class="block rounded-md px-3 py-2 text-base text-gray-900 font-medium hover:bg-gray-100 hover:text-gray-800">Profile</a>
                        <a href="#" class="block rounded-md px-3 py-2 text-base text-gray-900 font-medium hover:bg-gray-100 hover:text-gray-800">Resources</a>
                        <a href="#" class="block rounded-md px-3 py-2 text-base text-gray-900 font-medium hover:bg-gray-100 hover:text-gray-800">Company Directory</a>
                        <a href="#" class="block rounded-md px-3 py-2 text-base text-gray-900 font-medium hover:bg-gray-100 hover:text-gray-800">Openings</a>
                        </div>
                    </div>
                    <div class="pt-4 pb-2">
                        <div class="flex items-center px-5">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=facearea&amp;facepad=2&amp;w=256&amp;h=256&amp;q=80" alt="">
                        </div>
                        <div class="ml-3 min-w-0 flex-1">
                            <div class="text-base font-medium text-gray-800 truncate">Tom Cook</div>
                            <div class="text-sm font-medium text-gray-500 truncate">tom@example.com</div>
                        </div>
                        <button type="button" class="ml-auto flex-shrink-0 bg-white p-1 text-gray-400 rounded-full hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <span class="sr-only">View notifications</span>
                            <svg class="h-6 w-6" x-description="Heroicon name: outline/bell" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                        </button>
                        </div>
                        <div class="mt-3 px-2 space-y-1">
                        
                            <a href="#" class="block rounded-md px-3 py-2 text-base text-gray-900 font-medium hover:bg-gray-100 hover:text-gray-800">Your Profile</a>
                        
                            <a href="#" class="block rounded-md px-3 py-2 text-base text-gray-900 font-medium hover:bg-gray-100 hover:text-gray-800">Settings</a>
                        
                            <a href="#" class="block rounded-md px-3 py-2 text-base text-gray-900 font-medium hover:bg-gray-100 hover:text-gray-800">Sign out</a>
                        
                        </div>
                    </div>
                    </div>
                </div>
                
            </div>
        
        </header>

        {{-- Content --}}
        <main class="-mt-24 pb-8">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <div class="grid grid-cols-1 gap-4 items-start lg:grid-cols-3 lg:gap-8">
                    
                    {{-- Tabs --}}
                    <div class="grid grid-cols-1 gap-4 lg:col-span-2">
                        <section class="rounded-lg bg-white overflow-hidden shadow p-6">

                            {{-- Overview tab --}}
                            <div class="w-full" x-show="selectedTab === 1">
                                <h2 class="text-sm font-semibold text-black tracking-wide pb-4">
                                    @lang('messages.t_overview')
                                </h2>
                                <div class="w-full">

                                    {{-- Stats --}}
                                    <div class="flex justify-between items-center flex-wrap">
                                        
                                        {{-- Available balance --}}
                                        <div class="flex items-center text-center py-2">
                                            <div>
                                                <div class="flex items-center">
                                                    <div class="text-3xl font-bold text-gray-800">
                                                        @money(auth()->user()->balance_available, settings('currency')->code, true)
                                                    </div>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    @lang('messages.t_available_balance')
                                                </div>
                                            </div>
                                        </div>
                                        
                                        {{-- Divider --}}
                                        <div class="hidden md:block w-px h-8 bg-gray-200 ltr:mr-5 rtl:ml-5" aria-hidden="true"></div>

                                        {{-- Price --}}
                                        @if ($project->awarded_bid && $project->awarded_bid?->is_freelancer_accepted)
                                            <div class="flex items-center text-center py-2">
                                                <div>
                                                    <div class="flex items-center">
                                                        <div class="text-3xl font-bold text-gray-800">
                                                            @money($project->awarded_bid->amount, settings('currency')->code, true)
                                                        </div>
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        @lang('messages.t_price')
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Divider --}}
                                            <div class="hidden md:block w-px h-8 bg-gray-200 ltr:mr-5 rtl:ml-5" aria-hidden="true"></div>

                                        @endif

                                        {{-- Paid --}}
                                        <div class="flex items-center text-center py-2">
                                            <div>
                                                <div class="flex items-center">
                                                    <div class="text-3xl font-bold text-gray-800">
                                                        @money($paid_amount, settings('currency')->code, true)
                                                    </div>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    @lang('messages.t_paid')
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>

                                    {{-- Project --}}
                                    <div class="flex flex-col mt-8 bg-white overflow-hidden">
                                        <div class="grow w-full flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-6">
                                            <div class="grow">
                        
                                                {{-- Category --}}
                                                <a href="{{ url('explore/projects', $project->category->slug) }}" class="text-gray-500 tracking-wide text-xs font-normal hover:underline hover:text-gray-600">
                                                    {{ category_title('projects', $project->category) }}
                                                </a>
                        
                                                {{-- Title --}}
                                                <a href="{{ url('project/' . $project->pid . '/' . $project->slug) }}" class="font-semibold md:text-lg text-zinc-900 hover:text-primary-600 flex">
                                                    {{ $project->title }}
                                                </a>
                        
                                                {{-- Details --}}
                                                <div class="flex items-center mt-1">
                        
                                                    {{-- Bids --}}
                                                    <div class="flex items-center">
                                                        <svg class="w-5 h-5 text-gray-400 ltr:mr-2 rtl:ml-2" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M11.707 2.293A.997.997 0 0 0 11 2H6a.997.997 0 0 0-.707.293l-3 3A.996.996 0 0 0 2 6v5c0 .266.105.52.293.707l10 10a.997.997 0 0 0 1.414 0l8-8a.999.999 0 0 0 0-1.414l-10-10zM13 19.586l-9-9V6.414L6.414 4h4.172l9 9L13 19.586z"></path><circle cx="8.353" cy="8.353" r="1.647"></circle></svg>
                                                        <span class="text-sm text-gray-400">{{ $project->bids_count }} {{ strtolower(__('messages.t_bids')) }}</span>
                                                    </div>
                        
                                                    {{-- Budget type --}}
                                                    <div class="flex items-center ltr:ml-4 ltr:pl-4 ltr:border-l rtl:mr-4 rtl:pr-4 rtl:border-r border-gray-300">
                                                        <svg class="w-5 h-5 text-gray-400 ltr:mr-2 rtl:ml-2" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M16 12h2v4h-2z"></path><path d="M20 7V5c0-1.103-.897-2-2-2H5C3.346 3 2 4.346 2 6v12c0 2.201 1.794 3 3 3h15c1.103 0 2-.897 2-2V9c0-1.103-.897-2-2-2zM5 5h13v2H5a1.001 1.001 0 0 1 0-2zm15 14H5.012C4.55 18.988 4 18.805 4 18V8.815c.314.113.647.185 1 .185h15v10z"></path></svg>
                                                        @if ($project->budget_type === 'fixed')
                                                            <span class="text-sm text-gray-400">{{ __('messages.t_fixed_price') }}</span>
                                                        @else
                                                            <span class="text-sm text-gray-400">{{ __('messages.t_hourly_price') }}</span>
                                                        @endif
                                                    </div>
                                    
                                                    {{-- Created at --}}
                                                    <div class="flex items-center ltr:ml-4 ltr:pl-4 ltr:border-l rtl:mr-4 rtl:pr-4 rtl:border-r border-gray-300">
                                                        <svg class="w-5 h-5 text-gray-400 ltr:mr-2 rtl:ml-2" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"></path><path d="M13 7h-2v5.414l3.293 3.293 1.414-1.414L13 11.586z"></path></svg>
                                                        <span class="text-sm text-gray-400">{{ format_date($project->created_at, 'ago') }}</span>
                                                    </div>
                        
                                                </div>
                        
                                                {{-- Description --}}
                                                <p class="leading-relaxed text-gray-500 mt-4">
                                                    {{ nl2br($project->description) }}
                                                </p>
                                                
                                                {{-- Skills --}}
                                                <div class="space-x-2 rtl:space-x-reverse mt-4">
                                                    @foreach ($project->skills as $skill)
                                                        <div class="font-semibold inline-flex px-3 py-2.5 leading-4 text-xs rounded text-gray-600 bg-gray-100">
                                                            {{ $skill->skill->name }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                
                                            </div>
                        
                                            {{-- Right side --}}
                                            <div class="flex-none grid items-center md:w-48">
                        
                                                {{-- Budget --}}
                                                <div class="p-3 bg-gray-100 rounded-lg">
                        
                                                    <div class="flex items-center justify-center space-x-2 rtl:space-x-reverse">
                        
                                                        {{-- Min price --}}
                                                        <span class="text-black dark:text-white font-semibold text-base">@money($project->budget_min, settings('currency')->code, true)</span>
                                
                                                        {{-- Divider --}}
                                                        <span class="text-sm text-gray-400 px-1">/</span>
                                
                                                        {{-- Max price --}}
                                                        <span class="text-black dark:text-white font-semibold text-base">@money($project->budget_max, settings('currency')->code, true)</span>
                        
                                                    </div> 
                        
                                                </div>
                        
                                                {{-- Urgent --}}
                                                @if ($project->is_urgent)
                                                    <span class="flex items-center justify-center relative">
                                                        <span class="text-xs uppercase font-semibold tracking-wider text-red-500 animate-pulse">
                                                            {{ __('messages.t_urgent_project') }}
                                                        </span>
                                                    </span>
                                                @endif
                        
                                            </div>
                        
                                        </div>
                                    </div>

                                </div>
                            </div>

                            {{-- Milestones tab --}}
                            <div class="w-full" x-show="selectedTab === 2">
                                <div class="flex items-center justify-between mb-6">
                                    <h2 class="text-sm font-semibold text-black tracking-wide">
                                        @lang('messages.t_milestones')
                                    </h2>
                                    @if (in_array($project->status, ['active', 'under_development', 'pending_final_review']))
                                        <div class="text-center sm:text-right">
                                            <button type="button" id="modal-create-milestone-button-{{ $project->uid }}" class="inline-flex justify-center items-center space-x-2 rtl:space-x-reverse border font-semibold focus:outline-none px-3 py-2 leading-5 text-xs rounded border-gray-300 bg-white text-gray-800 shadow-sm hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 hover:shadow focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-white active:border-white active:shadow-none">
                                                <svg class="inline-block w-5 h-5 text-gray-500" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g><path fill="none" d="M0 0h24v24H0z"></path><path d="M22 6h-7a6 6 0 1 0 0 12h7v2a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h18a1 1 0 0 1 1 1v2zm-7 2h8v8h-8a4 4 0 1 1 0-8zm0 3v2h3v-2h-3z"></path></g></svg>
                                                <span>@lang('messages.t_create_milestone')</span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="overflow-x-auto min-w-full bg-white">
                                        <table class="min-w-full text-sm align-middle whitespace-nowrap">
                                            
                                            <thead>
                                                <tr>
                                                    <th class="p-3 text-slate-600 bg-slate-100 font-bold text-[10px] uppercase tracking-widest text-center">
                                                        @lang('messages.t_date')
                                                    </th>
                                                    <th class="px-7 py-3 text-slate-600 bg-slate-100 font-bold text-[10px] uppercase tracking-widest ltr:text-left rtl:text-right max-w-xs">
                                                        @lang('messages.t_description')
                                                    </th>
                                                    <th class="p-3 text-slate-600 bg-slate-100 font-bold text-[10px] uppercase tracking-widest text-center">
                                                        @lang('messages.t_amount')
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
                                                @foreach ($project->milestones as $m)
                                                    <tr wire:key="project-milestones-{{ $m->uid }}">

                                                        {{-- Date --}}
                                                        <td class="p-3 text-center text-zinc-500 font-medium text-[13px]">
                                                            {{ format_date($m->created_at, 'd-m-Y') }}
                                                        </td>

                                                        {{-- Description --}}
                                                        <td class="px-7 py-3 ltr:text-left rtl:text-right max-w-xs">
                                                            <span class="text-zinc-500 font-medium text-[13px] truncate max-w-[13rem] block" title="{{ $m->description }}">{{ $m->description }}</span>
                                                        </td>

                                                        {{-- Amount --}}
                                                        <td class="p-3 text-center text-zinc-900 font-semibold text-[13px]">
                                                            @money($m->amount, settings('currency')->code, true)
                                                        </td>

                                                        {{-- Status --}}
                                                        <td class="p-3 text-center">
                                                            @switch($m->status)

                                                                {{-- Paid --}}
                                                                @case('paid')
                                                                    <div class="bg-green-100 text-green-600 text-xs font-medium tracking-wide px-3 py-1.5 rounded-sm inline">
                                                                        @lang('messages.t_paid')
                                                                    </div>
                                                                    @break

                                                                {{-- Funded --}}
                                                                @case('funded')
                                                                    <div class="bg-blue-100 text-blue-600 text-xs font-medium tracking-wide px-3 py-1.5 rounded-sm inline">
                                                                        @lang('messages.t_funded')
                                                                    </div>
                                                                    @break

                                                                {{-- Requested --}}
                                                                @case('request')
                                                                    <div class="bg-amber-100 text-amber-600 text-xs font-medium tracking-wide px-3 py-1.5 rounded-sm inline">
                                                                        @lang('messages.t_requested')
                                                                    </div>
                                                                    @break
                                                                    
                                                                @default
                                                                    
                                                            @endswitch
                                                        </td>

                                                        {{-- Options --}}
                                                        <td class="p-4 text-center">
                                                            <div class="flex items-center justify-center space-x-4 rtl:space-x-reverse">

                                                                {{-- Release --}}
                                                                @if ($m->status === 'funded')
                                                                    <div>
                                                                        <button type="button" data-tooltip-target="tooltip-actions-release-{{ $m->uid }}" id="modal-release-payment-button-{{ $m->uid }}" class="inline-flex justify-center items-center border font-semibold focus:outline-none w-8 h-8 leading-5 text-sm rounded border-gray-300 bg-white text-gray-800 shadow-sm hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 hover:shadow focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-white active:border-white active:shadow-none">
                                                                            <svg class="w-4 h-4" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><g><path fill="none" d="M0 0h24v24H0z"></path><path d="M17 13v1c0 2.77-.664 5.445-1.915 7.846l-.227.42-1.747-.974c1.16-2.08 1.81-4.41 1.882-6.836L15 14v-1h2zm-6-3h2v4l-.005.379a12.941 12.941 0 0 1-2.691 7.549l-.231.29-1.55-1.264a10.944 10.944 0 0 0 2.471-6.588L11 14v-4zm1-4a5 5 0 0 1 5 5h-2a3 3 0 0 0-6 0v3c0 2.235-.82 4.344-2.271 5.977l-.212.23-1.448-1.38a6.969 6.969 0 0 0 1.925-4.524L7 14v-3a5 5 0 0 1 5-5zm0-4a9 9 0 0 1 9 9v3c0 1.698-.202 3.37-.597 4.99l-.139.539-1.93-.526c.392-1.437.613-2.922.658-4.435L19 14v-3A7 7 0 0 0 7.808 5.394L6.383 3.968A8.962 8.962 0 0 1 12 2zM4.968 5.383l1.426 1.425a6.966 6.966 0 0 0-1.39 3.951L5 11 5.004 13c0 1.12-.264 2.203-.762 3.177l-.156.29-1.737-.992c.38-.665.602-1.407.646-2.183L3.004 13v-2a8.94 8.94 0 0 1 1.964-5.617z"></path></g></svg>
                                                                        </button>
                                                                        <x-forms.tooltip id="tooltip-actions-release-{{ $m->uid }}" :text="__('messages.t_release_payment')" />
                                                                    </div>
                                                                @endif

                                                                {{-- Add funds --}}
                                                                @if ($m->status === 'request')
                                                                <div>
                                                                    <button type="button" data-tooltip-target="tooltip-actions-add-funds-{{ $m->uid }}" id="modal-delete-payment-button-{{ $m->uid }}" class="inline-flex justify-center items-center border font-semibold focus:outline-none w-8 h-8 leading-5 text-sm rounded border-gray-300 bg-white text-gray-800 shadow-sm hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 hover:shadow focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-white active:border-white active:shadow-none">
                                                                        <svg class="w-4 h-4" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g><path fill="none" d="M0 0h24v24H0z"></path><path d="M22 6h-7a6 6 0 1 0 0 12h7v2a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h18a1 1 0 0 1 1 1v2zm-7 2h8v8h-8a4 4 0 1 1 0-8zm0 3v2h3v-2h-3z"></path></g></svg>
                                                                    </button>
                                                                    <x-forms.tooltip id="tooltip-actions-add-funds-{{ $m->uid }}" :text="__('messages.t_add_funds')" />
                                                                </div>
                                                                @endif

                                                            </div>
                                                        </td>

                                                    </tr>

                                                    {{-- Release modal --}}
                                                    <x-forms.modal id="modal-release-payment-container-{{ $m->uid }}" target="modal-release-payment-button-{{ $m->uid }}" uid="modal_release_payment_{{ $m->uid }}" placement="center-center" size="max-w-md">

                                                        {{-- Modal heading --}}
                                                        <x-slot name="title">{{ __('messages.t_release_payment') }}</x-slot>
                            
                                                        {{-- Modal content --}}
                                                        <x-slot name="content">
                                                            <div class="text-center">
                                                                <div class="font-semibold text-zinc-600 text-sm">
                                                                    @lang('messages.t_confirm_release_of_pymnt_for') {{ $project->awarded_bid?->user?->username }}
                                                                </div>
                                                                <div class="text-base font-bold text-zinc-800 bg-gray-100 mt-4 py-1.5 rounded px-5 inline-block">
                                                                    @money($m->amount, settings('currency')->code, true)
                                                                </div>
                                                                <div class="mt-4 text-gray-500 leading-relaxed tracking-wide text-sm">
                                                                    @lang('messages.t_pls_ensure_u_are_satisfied_with_work_release_milestone')
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
                            
                                                                {{-- Release --}}
                                                                <button
                                                                    type="button" 
                                                                    wire:click="release('{{ $m->id }}')"
                                                                    wire:loading.attr="disabled"
                                                                    class="inline-flex justify-center items-center rounded border font-semibold focus:outline-none px-3 py-2 leading-5 text-xs tracking-wide border-transparent bg-green-500 text-white hover:bg-green-600 focus:ring focus:ring-green-500 focus:ring-opacity-25 disabled:bg-gray-200 disabled:hover:bg-gray-200 disabled:text-gray-500 disabled:cursor-not-allowed">
                                                                    
                                                                    {{-- Loading indicator --}}
                                                                    <div wire:loading wire:target="release('{{ $m->id }}')">
                                                                        <svg role="status" class="inline w-4 h-4 text-gray-700 animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                            <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                                                                            <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                                                                        </svg>
                                                                    </div>
                            
                                                                    {{-- Button text --}}
                                                                    <div wire:loading.remove wire:target="release('{{ $m->id }}')">
                                                                        @lang('messages.t_release_payment')
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
                            </div>

                            {{-- Shared files tab --}}
                            <div class="w-full" x-show="selectedTab === 3">
                                <h2 class="text-sm font-semibold text-black tracking-wide pb-4">
                                    @lang('messages.t_shared_files')
                                </h2>
                                <div class="h-96 border-2 border-dashed border-gray-200 rounded-lg"></div>
                            </div>
                            
                        </section>
                    </div>
        
                    {{-- Messages --}}
                    <div class="grid grid-cols-1 gap-4">
                        <section class="rounded-lg bg-white overflow-hidden shadow p-6">
                            <h2 class="text-sm font-semibold text-black tracking-wide pb-4">@lang('messages.t_messages')</h2>
                            <div class="h-96 border-2 border-dashed border-gray-200 rounded-lg"></div>
                        </section>
                    </div>

                </div>
            </div>
        </main>

        {{-- Create Milestone Modal --}}
        <x-forms.modal id="modal-create-milestone-container-{{ $project->uid }}" target="modal-create-milestone-button-{{ $project->uid }}" uid="modal_create_milestone_{{ $project->uid }}" placement="center-center" size="max-w-md">

            {{-- Modal heading --}}
            <x-slot name="title">{{ __('messages.t_create_milestone_payment') }}</x-slot>

            {{-- Modal content --}}
            <x-slot name="content">
                <div>

                    {{-- Stats --}}
                    <div class="md:flex md:items-center justify-between">
                        
                        {{-- Your budget --}}
                        <div class="flex flex-col items-center">
    
                            {{-- Alert icon --}}
                            <div class="h-20 w-20 flex items-center justify-center bg-slate-100 rounded-full">
                                <svg class="w-8 h-8 text-slate-400" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g><path fill="none" d="M0 0h24v24H0z"></path><path d="M10 20H6v2H4v-2H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h7V1.59a.5.5 0 0 1 .582-.493l10.582 1.764a1 1 0 0 1 .836.986V6h1v2h-1v7h1v2h-1v2.153a1 1 0 0 1-.836.986L20 20.333V22h-2v-1.333l-7.418 1.236A.5.5 0 0 1 10 21.41V20zm2-.36l8-1.334V4.694l-8-1.333v16.278zM16.5 14c-.828 0-1.5-1.12-1.5-2.5S15.672 9 16.5 9s1.5 1.12 1.5 2.5-.672 2.5-1.5 2.5z"></path></g></svg>
                            </div>
    
                            {{-- Budget --}}
                            <div class="mt-2 font-semibold text-sm text-slate-500">
                                @lang('messages.t_available_balance')
                            </div>
    
                            {{-- Available --}}
                            <div class="text-center leading-relaxed font-black text-base mt-1 text-zinc-700">
                                @money(auth()->user()->balance_available, settings('currency')->code, true)
                            </div>
    
                        </div>

                        {{-- Price --}}
                        <div class="flex flex-col items-center">
    
                            {{-- Icon --}}
                            <div class="h-20 w-20 flex items-center justify-center bg-slate-100 rounded-full">
                                <svg class="w-8 h-8 text-slate-400" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g><path fill="none" d="M0 0h24v24H0z"></path><path d="M21 3a1 1 0 0 1 1 1v5.5a2.5 2.5 0 1 0 0 5V20a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v-5.5a2.5 2.5 0 1 0 0-5V4a1 1 0 0 1 1-1h18z"></path></g></svg>
                            </div>
    
                            {{-- Budget --}}
                            <div class="mt-2 font-semibold text-sm text-slate-500">
                                @lang('messages.t_price')
                            </div>
    
                            {{-- Available --}}
                            <div class="text-center leading-relaxed font-black text-base mt-1 text-zinc-700">
                                @money($project->awarded_bid?->amount, settings('currency')->code, true)
                            </div>
    
                        </div>

                        {{-- Paid --}}
                        <div class="flex flex-col items-center">
    
                            {{-- Icon --}}
                            <div class="h-20 w-20 flex items-center justify-center bg-slate-100 rounded-full">
                                <svg class="w-8 h-8 text-slate-400" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g><path fill="none" d="M0 0h24v24H0z"></path><path d="M2 9h19a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V9zm1-6h15v4H2V4a1 1 0 0 1 1-1zm12 11v2h3v-2h-3z"></path></g></svg>
                            </div>
    
                            {{-- Budget --}}
                            <div class="mt-2 font-semibold text-sm text-slate-500">
                                @lang('messages.t_paid')
                            </div>
    
                            {{-- Available --}}
                            <div class="text-center leading-relaxed font-black text-base mt-1 text-zinc-700">
                                @money($paid_amount, settings('currency')->code, true)
                            </div>
    
                        </div>

                    </div>

                    {{-- Form --}}
                    <div class="grid grid-cols-1 gap-y-6 mt-6">
		
                        {{-- Amount --}}
                        <div>
                            <div class="relative w-full">
    
                                {{-- Input --}}
                                <input wire:model.defer="milestone_amount" type="text" class="{{ $errors->first('milestone_amount') ? 'focus:ring-red-600 focus:border-red-600 border-red-500' : 'focus:ring-primary-600 focus:border-primary-600 border-gray-300' }} border text-gray-900 text-sm rounded-lg font-medium block w-full ltr:pr-12 rtl:pl-12 p-4  dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="0.00">
    
                                {{-- Currency code --}}
                                <div class="absolute inset-y-0 ltr:right-0 rtl:left-0 flex items-center ltr:pr-3 rtl:pl-3 font-bold text-xs tracking-widest dark:text-gray-300 uppercase">
                                    {{ settings('currency')->code }}
                                </div>
    
                            </div>
    
                            {{-- Error --}}
                            @error('milestone_amount')
                                <p class="mt-1.5 text-[13px] tracking-wide text-red-600 font-medium ltr:pl-1 rtl:pr-1">
                                    {{ $errors->first('milestone_amount') }}
                                </p>
                            @enderror
    
                        </div>

                        {{-- Description --}}
                        <div>
                            <div class="relative w-full">
    
                                {{-- Input --}}
                                <textarea wire:model.defer="milestone_description" type="text" class="{{ $errors->first('milestone_description') ? 'focus:ring-red-600 focus:border-red-600 border-red-500' : 'focus:ring-primary-600 focus:border-primary-600 border-gray-300' }} border text-gray-900 text-sm rounded-lg font-medium block w-full ltr:pr-12 rtl:pl-12 p-4  dark:bg-zinc-700 dark:border-zinc-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" rows="6" placeholder="{{ __('messages.t_enter_description') }}"></textarea>
    
                                {{-- Currency code --}}
                                <div class="absolute inset-y-0 ltr:right-0 rtl:left-0 flex items-center ltr:pr-3 rtl:pl-3 dark:text-gray-300">
                                    <svg class="w-4 h-4" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g><path fill="none" d="M0 0h24v24H0z"></path><path d="M20 22H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1zM8 7v2h8V7H8zm0 4v2h8v-2H8zm0 4v2h8v-2H8z"></path></g></svg>
                                </div>
    
                            </div>
    
                            {{-- Error --}}
                            @error('milestone_description')
                                <p class="mt-1.5 text-[13px] tracking-wide text-red-600 font-medium ltr:pl-1 rtl:pr-1">
                                    {{ $errors->first('milestone_description') }}
                                </p>
                            @enderror
    
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

                    {{-- Create --}}
                    <button
                        type="button" 
                        wire:click="milestone"
                        wire:loading.attr="disabled"
                        class="inline-flex justify-center items-center rounded border font-semibold focus:outline-none px-3 py-2 leading-5 text-xs tracking-wide border-transparent bg-primary-500 text-white hover:bg-primary-600 focus:ring focus:ring-primary-500 focus:ring-opacity-25 disabled:bg-gray-200 disabled:hover:bg-gray-200 disabled:text-gray-500 disabled:cursor-not-allowed">
                        
                        {{-- Loading indicator --}}
                        <div wire:loading wire:target="milestone">
                            <svg role="status" class="inline w-4 h-4 text-gray-700 animate-spin" viewBox="0 0 100 101" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z" fill="#E5E7EB"/>
                                <path d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z" fill="currentColor"/>
                            </svg>
                        </div>

                        {{-- Button text --}}
                        <div wire:loading.remove wire:target="milestone">
                            @lang('messages.t_create')
                        </div>

                    </button>

                </div>
            </x-slot>

        </x-forms.modal>

    </div>
</div>

@push('scripts')
    <script src="{{ url('public/js/components.js') }}"></script>
    <script>
        function ltbiSPVMBMrZWHY() {
            return {
                selectedTab: 1
            }
        }
        window.ltbiSPVMBMrZWHY = ltbiSPVMBMrZWHY();
    </script>
@endpush