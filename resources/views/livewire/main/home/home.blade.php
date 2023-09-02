<div class="w-full">
    <div class="grid grid-cols-12 gap-6">

        {{-- Fatured categories --}}
        @if (settings('appearance')->is_featured_categories && $categories && $categories->count())
            <div class="col-span-12 mt-6 xl:mt-6 mb-16">
                <span class="font-semibold text-gray-400 dark:text-gray-200 uppercase tracking-wider text-center block">{{ __('messages.t_featured_categories') }}</span>
                <div class="flex-wrap justify-center items-center mt-8 -mx-5 hidden" id="featured-categories-slick" wire:ignore>

                    @foreach ($categories as $category)
                    <a href="{{ url('categories', $category->slug) }}" class="relative !h-44 rounded-lg !p-6 !flex !flex-col overflow-hidden group mx-5">
                        <span aria-hidden="true" class="absolute inset-0">
                            <img src="{{ placeholder_img() }}" data-src="{{ src($category->image) }}" alt="{{ $category->name }}" class="lazy w-full h-full object-center object-cover opacity-70 group-hover:opacity-100">
                        </span>
                        <span aria-hidden="true" class="absolute inset-x-0 bottom-0 h-2/3 bg-gradient-to-t from-gray-800 opacity-90"></span>
                        <span class="relative mt-auto text-center text-xl font-bold text-white">{{ $category->name }}</span>
                    </a>
                    @endforeach
                            
                </div>
            </div>
        @endif

        {{-- Bestsellers --}}
        @if (settings('appearance')->is_best_sellers && $sellers && $sellers->count())
            <div class="col-span-12 mt-6 xl:mt-6 mb-16">
                <span class="font-semibold text-gray-400 dark:text-gray-200 uppercase tracking-wider text-center block">{{ __('messages.t_top_sellers') }}</span>
                <a href="{{ url('sellers') }}" class="mt-1 text-primary-600 hover:text-primary-700 text-xs font-medium uppercase tracking-widest text-center block">{{ __('messages.t_view_more') }}</a>

                <ul class="flex-wrap justify-center items-center mt-8 -mx-5 hidden" id="top-sellers-slick" wire:ignore>
                    @foreach ($sellers as $seller)
                    <li class="col-span-1 flex flex-col text-center bg-white dark:bg-zinc-800 rounded-md shadow divide-y divide-gray-200 dark:divide-zinc-700 mx-5">
                        <div class="px-4 py-8">
        
                            {{-- Avatar --}}
                            <a href="{{ url('profile', $seller->username) }}" target="_blank" class="inline-block relative">
                                <img class="h-16 w-16 rounded-full object-cover lazy" src="{{ placeholder_img() }}" data-src="{{ src($seller->avatar) }}" alt="{{ $seller->username }}">
                                @if ($seller->isOnline() && !$seller->availability)
                                    <span class="absolute top-0.5 ltr:right-0.5 rtl:left-0.5 block h-3 w-3 rounded-full ring-2 ring-white dark:ring-zinc-800 bg-green-400"></span>
                                @elseif ($seller->availability)
                                    <span class="absolute top-0.5 ltr:right-0.5 rtl:left-0.5 block h-3 w-3 rounded-full ring-2 ring-white dark:ring-zinc-800 bg-gray-400"></span>
                                @else
                                    <span class="absolute top-0.5 ltr:right-0.5 rtl:left-0.5 block h-3 w-3 rounded-full ring-2 ring-white dark:ring-zinc-800 bg-red-400"></span>
                                @endif
                            </a>

                            {{-- Username --}}
                            <a href="{{ url('profile', $seller->username) }}" target="_blank" class="mt-4 text-gray-900 dark:text-gray-200 text-sm font-bold tracking-wider flex items-center justify-center">
                                {{ $seller->username }}
                                @if ($seller->status === 'verified')
                                    <img data-tooltip-target="tooltip-account-verified-{{ $seller->id }}" class="ltr:ml-0.5 rtl:mr-0.5 h-4 w-4 -mt-0.5" src="{{ url('public/img/auth/verified-badge.svg') }}" alt="{{ __('messages.t_account_verified') }}">
                                    <div id="tooltip-account-verified-{{ $seller->id }}" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-xs font-medium text-white bg-gray-900 rounded-sm shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                        {{ __('messages.t_account_verified') }}
                                    </div>
                                @endif
                            </a>

                            <dl class="mt-1 flex-grow flex flex-col justify-between">
                                <dt class="sr-only">Level</dt>
                                <dd class="text-[11px] font-medium uppercase tracking-widest" style="color:{{ $seller->level->level_color }}">{{ $seller->level->title }}</dd>
                                <dt class="sr-only">Skills</dt>
                                <dd class="mt-5 space-x-1 rtl:space-x-reverse">

                                    {{-- Rating --}}
                                    <div class="flex items-center justify-center mb-5" wire:ignore>

                                        {{-- Star --}}
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor"> <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        
                                        {{-- Rating --}}
                                        @if ($seller->rating() == 0)
                                            <div class=" text-[13px] tracking-widest text-amber-500 ltr:ml-1 rtl:mr-1 font-black">{{ __('messages.t_n_a') }}</div>
                                        @else
                                            <div class=" text-sm tracking-widest text-amber-500 ltr:ml-1 rtl:mr-1 font-black">{{ $seller->rating() }}</div>
                                        @endif
                        
                                        {{-- Reviews --}}
                                        <div class="ltr:ml-2 rtl:mr-2 text-[13px] font-normal text-gray-400 dark:text-gray-300">
                                            ( {{ number_format($seller->reviews()->count()) }} )
                                        </div>
                                        
                                    </div>

                                    {{-- Skills --}}
                                    @if ($seller->skills()->count())
                                        <div class="h-16">
                                            @foreach ($seller->skills()->InRandomOrder()->limit(3)->get() as $skill)
                                                <span class="inline-flex mb-2 px-3 py-1.5 items-center text-gray-800 text-xs font-medium bg-gray-100 dark:bg-zinc-700 dark:text-zinc-300 rounded-full">
                                                    {{ $skill->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="h-16"></div>
                                    @endif
                                    
                                </dd>
                            </dl>
        
                        </div>
        
                        {{-- Actions --}}
                        <div>
                            <div class="-mt-px flex divide-x divide-gray-200 rtl:divide-x-reverse bg-gray-100 dark:bg-zinc-700 dark:divide-zinc-700 rounded-b-lg">
        
                                @auth
                                    {{-- Contact me --}}
                                    <div class="w-0 flex-1 flex">
                                        <a href="{{ url('messages/new', $seller->username) }}" class="relative w-0 flex-1 inline-flex items-center justify-center py-4 text-xs text-gray-700 dark:text-zinc-300 dark:hover:text-zinc-100 font-medium border border-transparent rounded-bl-lg hover:text-gray-500">
                                            <svg class="w-5 h-5 text-gray-400 dark:text-gray-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"> <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/> <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/> </svg>
                                            <span class="ml-2">{{ __('messages.t_contact_me') }}</span>
                                        </a>
                                    </div>
                                @endauth

                                @guest
                                    {{-- View my profile --}}
                                    <div class="w-0 flex-1 flex">
                                        <a href="{{ url('profile', $seller->username) }}" class="relative w-0 flex-1 inline-flex items-center justify-center py-4 text-xs text-gray-700 dark:text-zinc-300 dark:hover:text-zinc-100 font-medium border border-transparent rounded-br-lg hover:text-gray-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-400 dark:text-gray-300" viewBox="0 0 20 20" fill="currentColor"> <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/></svg>
                                            <span class="ml-2">{{ __('messages.t_view_profile') }}</span>
                                        </a>
                                    </div>
                                @endguest
        
                            </div>
                        </div>
        
                    </li>
                    @endforeach
                </ul>

            </div>
        @endif

        {{-- Random gigs --}}
        @if ($gigs && !$gigs->isEmpty())
            <div class="col-span-12 mb-16">

                {{-- Section title --}}
                <div class="block mb-6">
                    <div class="flex justify-between items-center bg-transparent py-2">

                        <div>
                            <span class="font-extrabold text-xl text-gray-800 dark:text-gray-100 pb-1 block">
                                @lang('messages.t_selected_gigs_for_u')    
                            </span>
                        </div>

                        <div>
                            <a href="{{ url('search') }}" class="hidden text-sm font-semibold text-primary-600 hover:text-primary-700 sm:block">
                                {{ __('messages.t_view_more') }}
                                
                                {{-- LTR arrow --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden ltr:inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>

                                {{-- RTL arrow --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden rtl:inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                            </a>
                        </div>

                    </div>
                </div>

                <div class="grid grid-cols-12 sm:gap-x-9 gap-y-6">
                    @foreach ($gigs as $gig)
                        <div class="col-span-12 sm:col-span-6 md:col-span-6 lg:col-span-3 xl:col-span-3">
                            @livewire('main.cards.gig', ['gig' => $gig], key('gig-item-' . $gig->uid))
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Latest projects --}}
        @if (settings('projects')->is_enabled && !is_null($projects) && !$projects->isEmpty())
            <div class="col-span-12 mb-16">
            
                {{-- Section title --}}
                <div class="block mb-6">
                    <div class="flex justify-between items-center bg-transparent py-2">

                        <div>
                            <span class="font-extrabold text-xl text-gray-800 dark:text-gray-100 pb-1 block tracking-wider">
                                @lang('messages.t_latest_projects')    
                            </span>
                        </div>

                        <div>
                            <a href="{{ url('explore/projects') }}" class="hidden text-sm font-semibold text-primary-600 hover:text-primary-700 sm:block">
                                {{ __('messages.t_view_more') }}
                                
                                {{-- LTR arrow --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden ltr:inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>

                                {{-- RTL arrow --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden rtl:inline" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                            </a>
                        </div>

                    </div>
                </div>

                {{-- Projects --}}
                <div class="space-y-6">

                    @foreach ($projects as $project)
                        <div class="flex flex-col rounded shadow bg-white overflow-hidden {{ $project->is_highlighted ? 'ltr:border-l-[6px] rtl:border-r-[6px] border-primary-600' : '' }}">
                            <div class="p-5 lg:p-6 grow w-full flex flex-col md:flex-row space-y-4 md:space-y-0 md:space-x-6">
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
                                        {{ add_3_dots($project->description, 155) }}
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

                                    {{-- Bid now --}}
                                    <div class="flex flex-col">
                                        <a href="{{ url('project/' . $project->pid . '/' . $project->slug) }}" class="inline-flex justify-center items-center border font-semibold focus:outline-none px-3 py-3 leading-6 rounded border-gray-300 bg-white text-gray-800 shadow-sm hover:text-gray-800 hover:bg-gray-100 hover:border-gray-300 hover:shadow focus:ring focus:ring-gray-500 focus:ring-opacity-25 active:bg-white active:border-white active:shadow-none">
                                            <span class="text-sm">{{ __('messages.t_bid_now') }}</span>
                                        </a>
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
                    @endforeach

                </div>

            </div>
        @endif

        {{-- Newsletter --}}
        @if (settings('newsletter')->is_enabled)
            <div class="col-span-12">
                <div class="bg-gray-100 dark:bg-zinc-800 rounded-md p-6 flex items-center sm:p-10">
                    <div class="max-w-lg mx-auto">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ __('messages.t_sign_up_for_newsletter') }}</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-300">{{__('messages.t_sign_up_for_newsletter_subtitle')}}</p>
                        <div class="mt-4 sm:mt-6 sm:flex">
                            <label for="email-address" class="sr-only">Email address</label>
                            <input wire:model.defer="email" id="email-address" type="text" autocomplete="email" required="" placeholder="{{ __('messages.t_enter_email_address') }}"
                                class="h-14 appearance-none min-w-0 w-full bg-white dark:bg-zinc-700 border border-gray-300 dark:border-zinc-700 rounded-md shadow-sm py-2 px-4 text-sm text-gray-900 dark:text-gray-300 placeholder-gray-500 focus:outline-none focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                            <div class="mt-3 sm:flex-shrink-0 sm:mt-0 ltr:sm:ml-4 rtl:sm:mr-4">
                                <button wire:click="newsletter" wire:loading.attr="disabled" wire:target="newsletter" type="button" class="dark:disabled:bg-zinc-500 dark:disabled:text-zinc-400 disabled:cursor-not-allowed disabled:!bg-gray-400 disabled:text-gray-500 h-14 w-full bg-primary-600 border border-transparent rounded-md shadow-sm py-2 px-4 flex items-center justify-center text-sm font-bold tracking-wider text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-white focus:ring-primary-600">
                                    {{ __('messages.t_signup') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>

@push('scripts')

    {{-- Slick Plugin --}}
    @if (settings('appearance')->is_featured_categories && $categories && $categories->count())
        <script defer type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function(){
                // Init featured categories slick
                $('#featured-categories-slick').slick({
                    dots          : false,
                    autoplay      : true,
                    infinite      : true,
                    speed         : 300,
                    slidesToShow  : 6,
                    slidesToScroll: 1,
                    arrows        : false,
                    responsive    : [
                        {
                        breakpoint: 1024,
                            settings: {
                                slidesToShow  : 4,
                                slidesToScroll: 1
                            }
                        },
                        {
                        breakpoint: 800,
                            settings: {
                                slidesToShow  : 3,
                                slidesToScroll: 1
                            }
                        },
                        {
                        breakpoint: 600,
                            settings: {
                                slidesToShow  : 2,
                                slidesToScroll: 1
                            }
                        },
                        {
                        breakpoint: 480,
                            settings: {
                                slidesToShow  : 1,
                                slidesToScroll: 1
                            }
                        }
                    ]
                });
                $('#featured-categories-slick').removeClass('hidden');
            });
        </script>
    @endif

    {{-- Bestsellers --}}
    @if (settings('appearance')->is_best_sellers && $sellers && $sellers->count())
        <script>
            document.addEventListener("DOMContentLoaded", function(){
                // Init featured categories slick
                $('#top-sellers-slick').slick({
                    dots          : false,
                    autoplay      : true,
                    infinite      : true,
                    speed         : 300,
                    slidesToShow  : 5,
                    slidesToScroll: 1,
                    arrows        : false,
                    responsive    : [
                        {
                        breakpoint: 1024,
                            settings: {
                                slidesToShow  : 3,
                                slidesToScroll: 1
                            }
                        },
                        {
                        breakpoint: 800,
                            settings: {
                                slidesToShow  : 3,
                                slidesToScroll: 1
                            }
                        },
                        {
                        breakpoint: 600,
                            settings: {
                                slidesToShow  : 2,
                                slidesToScroll: 1
                            }
                        },
                        {
                        breakpoint: 480,
                            settings: {
                                slidesToShow  : 1,
                                slidesToScroll: 1
                            }
                        }
                    ]
                });
                $('#top-sellers-slick').removeClass('hidden');
            });
        </script>
    @endif
    
@endpush

@push('styles')

    {{-- Slick Plugin --}}
    @if (settings('appearance')->is_featured_categories)
        <link rel="preload" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" type="text/css" as="style" onload="this.onload=null;this.rel='stylesheet';"/>
    @endif
        
@endpush
