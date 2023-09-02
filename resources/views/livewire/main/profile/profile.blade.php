<div class="w-full" x-data="window.vjOXhkLsunWIxQy" x-init="init()" x-cloak>
    <div class="grid grid-cols-12 md:gap-x-6 gap-y-6">

        {{-- Check if user available --}}
        @if ($user->availability)
            <div class="col-span-12">
                <div class="rounded-md bg-amber-100 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-amber-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"> <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/> </svg>
                        </div>
                        <div class="ltr:ml-3 rtl:mr-3">
                            <h3 class="text-sm font-medium text-amber-800">{{ __('messages.t_attention_needed') }}</h3>
                            <div class="mt-2 text-sm text-amber-700">
                                <span class="text-xs font-medium mb-3 block">{{ __('messages.t_this_user_is_not_available_right_now_msg', ['date' => format_date($user->availability->expected_available_date, 'F j, Y')]) }}</span>
                                <div class="italic text-xs border-l-4 border-amber-500 pl-2">
                                    {{ $user->availability->message }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Profile details --}}
        <div class="col-span-12 lg:col-span-4">
            
            {{-- Profile header --}}
            <div class="bg-white dark:bg-zinc-800 border border-gray-100 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="md:flex">
                    <div class="w-full p-2 py-10">
                    
                        <div class="flex justify-center">
                            <div class="relative">
                
                                {{-- User avatar --}}
                                <img src="{{ placeholder_img() }}" data-src="{{ src($user->avatar) }}" alt="{{ $user->username }}" class="lazy rounded-full w-20 h-20 object-cover">

                                {{-- User status --}}
                                @if ($user->isOnline() && !$user->availability)
                                    {{-- Online --}}
                                    <span class="absolute border-white border-4 h-5 w-5 top-12 left-16 bg-green-400 rounded-full"></span>
                                @elseif ($user->availability)
                                    {{-- Unavailable --}}
                                    <span class="absolute border-white border-4 h-5 w-5 top-12 left-16 bg-gray-600 rounded-full"></span>
                                @else
                                    {{-- Offline --}}
                                    <span class="absolute border-white border-4 h-5 w-5 top-12 left-16 bg-red-600 rounded-full"></span>
                                @endif
                        
                            </div>
                        </div>
            
                        <div class="flex flex-col text-center mt-3 mb-4">
                            <span class="text-md font-extrabold text-gray-800 dark:text-gray-100 flex items-center justify-center">
                                {{ $user->username }}
                                @if ($user->status === 'verified')
                                    <img data-tooltip-target="tooltip-account-verified-{{ $user->id }}" class="ltr:ml-0.5 rtl:mr-0.5 h-4 w-4 -mt-0.5" src="{{ url('public/img/auth/verified-badge.svg') }}" alt="{{ __('messages.t_account_verified') }}">
                                    <div id="tooltip-account-verified-{{ $user->id }}" role="tooltip" class="inline-block absolute invisible z-10 py-2 px-3 text-xs font-medium text-white bg-gray-900 rounded-sm shadow-sm opacity-0 tooltip dark:bg-gray-700">
                                        {{ __('messages.t_account_verified') }}
                                    </div>
                                @endif
                            </span>
                            <span class="text-sm text-gray-400 dark:text-gray-300">{{ $user->headline }}</span>
                        </div>
            
                        <p class="px-16 text-center text-sm text-gray-500 dark:text-gray-200 italic">
                            {{ $user->description }}
                        </p>

                        {{-- Actions --}}
                        <div class="px-14 mt-8">

                            {{-- Contact user --}}
                            <a href="{{ url('messages/new', $user->username) }}" class="flex items-center justify-center h-12 bg-primary-600 w-full text-white text-sm font-medium rounded hover:shadow hover:bg-primary-700 {{ auth()->check() && auth()->id() !== $user->id ? 'mb-4' : '' }}">{{ __('messages.t_contact_me') }}</a>
                    
                            {{-- Report user --}}
                            @auth
                                @if (auth()->id() !== $user->id)
                                    <button id="modal-report-button" type="button" class="h-12 bg-gray-200 dark:bg-zinc-700 w-full text-black dark:text-zinc-300 text-sm font-medium rounded dark:hover:bg-zinc-600 hover:shadow hover:bg-gray-300 mb-2">{{ __('messages.t_report') }}</button>
                                @endif
                            @endauth
                            
                        </div>
                        
                    </div>
                
                </div>
            </div>

            {{-- Quick stats --}}
            <div class="bg-white dark:bg-zinc-800 border border-gray-100 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden mb-6">
                <div class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-zinc-700">
                    <h3 class="text-sm leading-6 font-semibold tracking-wide text-gray-900 dark:text-gray-100">
                        {{ __('messages.t_details') }}
                    </h3>
                    <p class="mt-1 max-w-2xl text-xs font-medium text-gray-500 dark:text-gray-300">
                        {{ __('messages.t_more_details_about_this_user') }}
                    </p>
                </div>
                <div class="border-t-2 border-gray-100 dark:border-zinc-700 px-4 py-5 sm:p-0">
                    <dl class="sm:divide-y sm:divide-gray-100 dark:divide-zinc-700">

                        {{-- Fullname --}}
                        @if ($user->fullname)
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('messages.t_fullname') }}
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200 sm:mt-0 sm:col-span-2">
                                    {{ $user->fullname }}
                                </dd>
                            </div>
                        @endif

                        {{-- Country --}}
                        @if ($user->country)
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('messages.t_country') }}
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200 sm:mt-0 sm:col-span-2 flex items-center">
                                    <img src="{{ placeholder_img() }}" data-src="{{ url('public/img/flags/rounded/'. strtolower($user->country?->code) .'.svg') }}" alt="{{ $user->country?->name }}" class="lazy h-5 w-5 ltr:mr-2 rtl:ml-2">  
                                    <span>{{ $user->country?->name }}</span>
                                </dd>
                            </div>
                        @endif

                        {{-- Level --}}
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('messages.t_level') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200 sm:mt-0 sm:col-span-2">
                                {{ $user->level?->title }}
                            </dd>
                        </div>

                        {{-- Joined date --}}
                        <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ __('messages.t_member_since') }}
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200 sm:mt-0 sm:col-span-2">
                                {{ format_date($user->created_at, 'F j, Y') }}
                            </dd>
                        </div>

                        {{-- Last delivery --}}
                        @if ($user->account_type === 'seller' && $last_delivery)
                            <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                    {{ __('messages.t_last_delivery') }}
                                </dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-200 sm:mt-0 sm:col-span-2">
                                    {{ format_date($last_delivery, 'ago') }}
                                </dd>
                            </div>
                        @endif
                        
                    </dl>
                </div>
            </div>

            {{-- Portfolio --}}
            @if ($user->account_type === 'seller' && count($user->projects))
                <div class="bg-white dark:bg-zinc-800 border border-gray-100 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden mb-6">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-zinc-700">
                        <h3 class="text-sm leading-6 font-semibold tracking-wide text-gray-900 dark:text-gray-100">
                            {{ __('messages.t_portfolio') }}
                        </h3>
                        <p class="mt-1 max-w-2xl text-xs font-medium text-gray-500 dark:text-gray-300">
                            {{ __('messages.t_see_my_latest_works') }}
                        </p>
                    </div>
                    <div class="border-t-2 border-gray-100 dark:border-zinc-700 px-4 py-5 sm:p-0">
                        <div class="px-6 py-5">
                            <div class="container-mansory mb-4">

                                @foreach ($user->projects as $project)
                                    @if ($project->status === 'active')
                                        <figure>
                                            <a href="{{ url('projects', $project->slug) }}" target="_blank">
                                                <img src="{{ placeholder_img() }}" data-src="{{ src($project->thumbnail) }}" alt="{{ $project->title }}" class="lazy rounded-md hover:opacity-75 object-cover" />
                                            </a>
                                        </figure>
                                    @endif
                                @endforeach
                                
                            </div>

                            <a href="{{ url('profile/' . $user->username . '/portfolio') }}" target="_blank" class="block mt-8 text-center text-xs font-bold text-primary-600 hover:underline">{{ __('messages.t_view_my_porfolio') }}</a>

                        </div>
                    </div>
                </div>
            @endif

            {{-- Skills --}}
            @if (count($user->skills))
                <div class="bg-white dark:bg-zinc-800 border border-gray-100 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden mb-6">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-zinc-700">
                        <h3 class="text-sm leading-6 font-semibold tracking-wide text-gray-900 dark:text-gray-100">
                            {{ __('messages.t_skills') }}
                        </h3>
                        <p class="mt-1 max-w-2xl text-xs font-medium text-gray-500 dark:text-gray-300">
                            {{ __('messages.t_user_skills_and_hobbies') }}
                        </p>
                    </div>
                    <div class="border-t-2 border-gray-100 dark:border-zinc-700 px-4 py-5 sm:p-0">
                        
                        {{-- List of skills --}}
                        @foreach ($user->skills as $skill)
                            <a href="{{ url('hire', $skill->slug) }}" class="block mb-1 px-6 py-5">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ $skill->name }}</span>
                                    <span class="text-xs font-normal text-gray-600 dark:text-gray-400">{{ __('messages.t_' . $skill->experience) }}</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    @switch($skill->experience)
                                        @case('beginner')
                                            <div class="bg-gray-500 h-2 rounded-full" style="width: 33%"></div>
                                            @break
                                        @case('intermediate')
                                            <div class="bg-primary-600 h-2 rounded-full" style="width: 67%"></div>
                                            @break
                                        @case('pro')
                                            <div class="bg-green-400 h-2 rounded-full" style="width: 100%"></div>
                                            @break
                                    @endswitch
                                </div>
                            </a>
                        @endforeach

                    </div>
                </div>
            @endif

            {{-- Linked accounts --}}
            @if ($user->accounts)
                <div class="bg-white dark:bg-zinc-800 border border-gray-100 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden mb-6">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-zinc-700">
                        <h3 class="text-sm leading-6 font-semibold tracking-wide text-gray-900 dark:text-gray-100">
                            {{ __('messages.t_linked_accounts') }}
                        </h3>
                        <p class="mt-1 max-w-2xl text-xs font-medium text-gray-500 dark:text-gray-300">
                            {{ __('messages.t_follow_me_on_other_social_networks') }}
                        </p>
                    </div>
                    <div class="border-t-2 border-gray-100 dark:border-zinc-700 px-4 py-5 sm:p-0">
                        <div class="px-6 py-5 space-x-2 space-y-4 text-center rtl:space-x-reverse">
                            
                            {{-- Facebook --}}
                            @if ($user->accounts->facebook_profile)
                                <a href="{{ url('redirect?to=' . safeEncrypt($user->accounts->facebook_profile)) }}" target="_blank" class="text-white bg-[#3b5998] hover:bg-[#3b5998]/90 focus:outline-none rounded-full text-center inline-flex items-center h-10 w-10 justify-center">
                                    <i class="si si-facebook"></i>
                                </a>
                            @endif

                            {{-- Twitter --}}
                            @if ($user->accounts->twitter_profile)
                                <a href="{{ url('redirect?to=' . safeEncrypt($user->accounts->twitter_profile)) }}" target="_blank" class="text-white bg-[#00acee] hover:bg-[#00acee]/90 focus:outline-none rounded-full text-center inline-flex items-center h-10 w-10 justify-center">
                                    <i class="si si-twitter"></i>
                                </a>
                            @endif

                            {{-- Dribbble --}}
                            @if ($user->accounts->dribbble_profile)
                                <a href="{{ url('redirect?to=' . safeEncrypt($user->accounts->dribbble_profile)) }}" target="_blank" class="text-white bg-[#ea4c89] hover:bg-[#ea4c89]/90 focus:outline-none rounded-full text-center inline-flex items-center h-10 w-10 justify-center">
                                    <i class="si si-dribbble"></i>
                                </a>
                            @endif

                            {{-- Stackoverflow --}}
                            @if ($user->accounts->stackoverflow_profile)
                                <a href="{{ url('redirect?to=' . safeEncrypt($user->accounts->stackoverflow_profile)) }}" target="_blank" class="text-white bg-[#ef8236] hover:bg-[#ef8236]/90 focus:outline-none rounded-full text-center inline-flex items-center h-10 w-10 justify-center">
                                    <i class="si si-stackoverflow"></i>
                                </a>
                            @endif

                            {{-- Github --}}
                            @if ($user->accounts->github_profile)
                                <a href="{{ url('redirect?to=' . safeEncrypt($user->accounts->github_profile)) }}" target="_blank" class="text-white bg-[#171515] hover:bg-[#171515]/90 focus:outline-none rounded-full text-center inline-flex items-center h-10 w-10 justify-center">
                                    <i class="si si-github"></i>
                                </a>
                            @endif

                            {{-- Youtube --}}
                            @if ($user->accounts->youtube_profile)
                                <a href="{{ url('redirect?to=' . safeEncrypt($user->accounts->youtube_profile)) }}" target="_blank" class="text-white bg-[#FF0000] hover:bg-[#FF0000]/90 focus:outline-none rounded-full text-center inline-flex items-center h-10 w-10 justify-center">
                                    <i class="si si-youtube"></i>
                                </a>
                            @endif

                            {{-- Vimeo --}}
                            @if ($user->accounts->vimeo_profile)
                                <a href="{{ url('redirect?to=' . safeEncrypt($user->accounts->vimeo_profile)) }}" target="_blank" class="text-white bg-[#86c9ef] hover:bg-[#86c9ef]/90 focus:outline-none rounded-full text-center inline-flex items-center h-10 w-10 justify-center">
                                    <i class="si si-vimeo"></i>
                                </a>
                            @endif

                        </div>
                    </div>
                </div>
            @endif

            {{-- Languages --}}
            @if (count($user->languages))
                <div class="bg-white dark:bg-zinc-800 border border-gray-100 dark:border-zinc-700 rounded-xl shadow-sm overflow-hidden mb-6">
                    <div class="px-4 py-5 sm:px-6 bg-gray-50 dark:bg-zinc-700">
                        <h3 class="text-sm leading-6 font-semibold tracking-wide text-gray-900 dark:text-gray-100">
                            {{ __('messages.t_languages') }}
                        </h3>
                        <p class="mt-1 max-w-2xl text-xs font-medium text-gray-500 dark:text-gray-300">
                            {{ __('messages.t_list_of_languages_i_speak') }}
                        </p>
                    </div>
                    <div class="border-t-2 border-gray-100 dark:border-zinc-700 px-4 py-5 sm:p-0">
                        <div class="py-5 grid items-center">

                            {{-- List of languages --}} 
                            <ul class="-my-5 divide-y divide-gray-100 dark:divide-zinc-700">
                                @foreach ($user->languages as $lang)
                                    <li class="py-4 px-6">
                                        <div class="flex items-center space-x-4 rtl:space-x-reverse">
                                            <div class="flex-shrink-0 h-8 w-8 rounded-full flex justify-center items-center bg-gray-100">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-bold text-gray-900 dark:text-gray-200 truncate">
                                                    {{ $lang->name }}
                                                </p>
                                            </div>
                                            <div>
                                                <span class="text-xs text-gray-400 dark:text-gray-300 font-medium">{{ __('messages.t_' . $lang->level) }}</span>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

        </div>

        {{-- Gigs --}}
        <div class="col-span-12 lg:col-span-8">
            <div class="grid grid-cols-12 md:gap-x-6 gap-y-6">

                {{-- List of gigs --}}
                @forelse ($gigs as $gig)
                            
                    {{-- Gig item --}}
                    <div class="col-span-12 lg:col-span-6 xl:col-span-4 md:col-span-6 sm:col-span-6">
                        @livewire('main.cards.gig', ['gig' => $gig, 'profile_visible' => false], key("gig-item-" . $gig->uid))
                    </div>

                @empty
                    
                    <div class="col-span-12">
                        <div class="py-14 px-6 text-center text-sm sm:px-14 border-dashed border-2">
                            <svg class="mx-auto h-6 w-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/> </svg>
                            <p class="mt-4 font-semibold text-gray-900">{{ __('messages.no_results_found') }}</p>
                            <p class="mt-2 text-gray-500">{{ __('messages.t_we_couldnt_find_anthing_search_term') }}</p>
                        </div>
                    </div>

                @endforelse

                {{-- Pages --}}
                @if ($gigs->hasPages())
                    <div class="col-span-12 flex justify-center pt-12">
                        {!! $gigs->links('pagination::tailwind') !!}
                    </div>
                @endif

            </div>
        </div>

    </div>

    {{-- Report user modal --}}
    @if (auth()->check() && auth()->id() !== $user->id)
        <x-forms.modal id="modal-report-container" target="modal-report-button" uid="modal_{{ uid() }}" placement="center-center" size="max-w-md">

            {{-- Header --}}
            <x-slot name="title">{{ __('messages.t_report_user') }}</x-slot>

            {{-- Content --}}
            <x-slot name="content">
                <div class="grid grid-cols-12 md:gap-x-6 gap-y-6">

                    {{-- Message --}}
                    <div class="col-span-12">
                        <x-forms.textarea
                            :label="__('messages.t_reason')"
                            :placeholder="__('messages.t_report_user_reason_placeholder')"
                            model="reason"
                            icon="message-question" />
                    </div>

                </div>
            </x-slot>

            {{-- Footer --}}
            <x-slot name="footer">
                <x-forms.button action="report" text="{{ __('messages.t_report') }}" :block="0"  />
            </x-slot>

        </x-forms.modal>
    @endif

</div>

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-icons-font@v5/font/simple-icons.min.css" type="text/css">
@endpush

@push('scripts')

    {{-- AlpineJs --}}
    <script>
        function vjOXhkLsunWIxQy() {
            return {

                open: false,

                // Init component
                init() {
                    
                }

            }
        }
        window.vjOXhkLsunWIxQy = vjOXhkLsunWIxQy();
    </script>

@endpush