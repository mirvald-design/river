<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ config()->get('direction') }}" @class(['dark' => Cookie::get('dark_mode') === 'enabled', 'h-full bg-gray-50'])>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<meta name="csrf-token" content="{{ csrf_token() }}">

		{{-- Generate seo tags --}}
		{!! SEO::generate() !!}

		{{-- Custom fonts --}}
		{!! settings('appearance')->font_link !!}

		{{-- Favicon --}}
		<link rel="icon" type="image/png" href="{{ src( settings('general')->favicon ) }}"/>

		{{-- Livewire styles --}}
		@livewireStyles

		{{-- Styles --}}
		<link rel="preload" href="{{ mix('css/app.css') }}" as="style">
		<link rel="stylesheet" href="{{ mix('css/app.css') }}">

		{{-- Preload Livewire --}}
		<link rel="preload" href="{{ livewire_asset_path() }}" as="script">

		{{-- Custom css --}}
		<style>
			:root {
				--color-primary-h: {{ hex2hsl( settings('appearance')->colors['primary'] )[0] }};
				--color-primary-s: {{ hex2hsl( settings('appearance')->colors['primary'] )[1] }}%;
				--color-primary-l: {{ hex2hsl( settings('appearance')->colors['primary'] )[2] }}%;
			}
			html {
				font-family: @php echo settings('appearance')->font_family @endphp, sans-serif !important;
			}
		</style>

		{{-- Styles --}}
		@stack('styles')

		{{-- JavaScript variables --}}
		<script>
			__var_app_url        = "{{ url('/') }}";
			__var_app_locale     = "{{ app()->getLocale() }}";
			__var_rtl            = @js(config()->get('direction') === 'ltr' ? false : true);
			__var_primary_color  = "{{ settings('appearance')->colors['primary'] }}";
			__var_axios_base_url = "{{ url('/') }}/";
			__var_currency_code  = "{{ settings('currency')->code }}";
		</script>

	</head>
	<body class="h-full overflow-hidden">
		<div class="h-full flex" x-data="{ open: false }" @keydown.window.escape="open = false">
			
			{{-- Sidebar --}}
			<div class="hidden w-28 bg-white border-gray-100 shadow-md ltr:border-r rtl:border-l overflow-y-auto md:block">
				<div class="w-full py-6 flex flex-col items-center">

					{{-- Website logo --}}
					<div class="flex-shrink-0 flex items-center">
						@if (Cookie::get('dark_mode') === 'enabled' && settings('general')->logo_dark)
							<a href="{{ url('/') }}" class="block">
								<img style="max-width: calc(100% - 15px); max-height: {{ settings('appearance')->sizes['header_desktop_logo_height'] }}px; margin: 0 auto;" src="{{ placeholder_img() }}" data-src="{{ src(settings('general')->logo_dark) }}" class="lazy" alt="{{ settings('general')->title }}" style="height: {{ settings('appearance')->sizes['header_desktop_logo_height'] }}px;width:auto">
							</a>
						@else
							<a href="{{ url('/') }}" class="block">
								<img style="max-width: calc(100% - 15px); max-height: {{ settings('appearance')->sizes['header_desktop_logo_height'] }}px; margin: 0 auto;" src="{{ placeholder_img() }}" data-src="{{ src(settings('general')->logo) }}" class="lazy" alt="{{ settings('general')->title }}" style="height: {{ settings('appearance')->sizes['header_desktop_logo_height'] }}px;width:auto">
							</a>
						@endif
					</div>

					{{-- Links --}}
					<div class="flex-1 mt-6 w-full px-2 space-y-1">

						{{-- Home --}}
						<a href="{{ url('seller/home') }}" class="group w-full p-3 rounded-md flex flex-col items-center text-xs font-medium {{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/home') ? 'bg-primary-600 text-white' : 'text-gray-500 hover:bg-primary-600 hover:text-white' }}">
							<svg class="h-6 w-6 {{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/home') ? 'text-white' : 'text-gray-500 group-hover:text-white' }}" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z"></path><path d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.43z"></path></svg>
							<span class="mt-2">@lang('messages.t_home')</span>
						</a>

						{{-- Orders --}}
						<a href="{{ url('seller/orders') }}" class="group w-full p-3 rounded-md flex flex-col items-center text-xs font-medium {{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/orders') ? 'bg-primary-600 text-white' : 'text-gray-500 hover:bg-primary-600 hover:text-white' }}">
							<svg class="{{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/orders') ? 'text-white' : 'text-gray-500 group-hover:text-white' }} h-6 w-6" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.5 6v.75H5.513c-.96 0-1.764.724-1.865 1.679l-1.263 12A1.875 1.875 0 004.25 22.5h15.5a1.875 1.875 0 001.865-2.071l-1.263-12a1.875 1.875 0 00-1.865-1.679H16.5V6a4.5 4.5 0 10-9 0zM12 3a3 3 0 00-3 3v.75h6V6a3 3 0 00-3-3zm-3 8.25a3 3 0 106 0v-.75a.75.75 0 011.5 0v.75a4.5 4.5 0 11-9 0v-.75a.75.75 0 011.5 0v.75z" clip-rule="evenodd"></path></svg>
							<span class="mt-2">@lang('messages.t_orders')</span>
						</a>

						{{-- Gigs --}}
						<a href="{{ url('seller/gigs') }}" class="group w-full p-3 rounded-md flex flex-col items-center text-xs font-medium {{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/gigs') ? 'bg-primary-600 text-white' : 'text-gray-500 hover:bg-primary-600 hover:text-white' }}">
							<svg class="{{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/gigs') ? 'text-white' : 'text-gray-500 group-hover:text-white' }} h-6 w-6" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M5.223 2.25c-.497 0-.974.198-1.325.55l-1.3 1.298A3.75 3.75 0 007.5 9.75c.627.47 1.406.75 2.25.75.844 0 1.624-.28 2.25-.75.626.47 1.406.75 2.25.75.844 0 1.623-.28 2.25-.75a3.75 3.75 0 004.902-5.652l-1.3-1.299a1.875 1.875 0 00-1.325-.549H5.223z"></path><path fill-rule="evenodd" d="M3 20.25v-8.755c1.42.674 3.08.673 4.5 0A5.234 5.234 0 009.75 12c.804 0 1.568-.182 2.25-.506a5.234 5.234 0 002.25.506c.804 0 1.567-.182 2.25-.506 1.42.674 3.08.675 4.5.001v8.755h.75a.75.75 0 010 1.5H2.25a.75.75 0 010-1.5H3zm3-6a.75.75 0 01.75-.75h3a.75.75 0 01.75.75v3a.75.75 0 01-.75.75h-3a.75.75 0 01-.75-.75v-3zm8.25-.75a.75.75 0 00-.75.75v5.25c0 .414.336.75.75.75h3a.75.75 0 00.75-.75v-5.25a.75.75 0 00-.75-.75h-3z" clip-rule="evenodd"></path></svg>
							<span class="mt-2">@lang('messages.t_gigs')</span>
						</a>

						{{-- Payouts --}}
						<a href="{{ url('seller/withdrawals') }}" class="group w-full p-3 rounded-md flex flex-col items-center text-xs font-medium {{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/withdrawals') ? 'bg-primary-600 text-white' : 'text-gray-500 hover:bg-primary-600 hover:text-white' }}">
							<svg class="{{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/withdrawals') ? 'text-white' : 'text-gray-500 group-hover:text-white' }} h-6 w-6" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M4.5 3.75a3 3 0 00-3 3v.75h21v-.75a3 3 0 00-3-3h-15z"></path><path fill-rule="evenodd" d="M22.5 9.75h-21v7.5a3 3 0 003 3h15a3 3 0 003-3v-7.5zm-18 3.75a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z" clip-rule="evenodd"></path></svg>
							<span class="mt-2">@lang('messages.t_payouts')</span>
						</a>

						{{-- Reviews --}}
						<a href="{{ url('seller/reviews') }}" class="group w-full p-3 rounded-md flex flex-col items-center text-xs font-medium {{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/reviews') ? 'bg-primary-600 text-white' : 'text-gray-500 hover:bg-primary-600 hover:text-white' }}">
							<svg class="{{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/reviews') ? 'text-white' : 'text-gray-500 group-hover:text-white' }} h-6 w-6" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"></path></svg>
							<span class="mt-2">@lang('messages.t_reviews')</span>
						</a>

						{{-- Earnings --}}
						<a href="{{ url('seller/earnings') }}" class="group w-full p-3 rounded-md flex flex-col items-center text-xs font-medium {{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/earnings') ? 'bg-primary-600 text-white' : 'text-gray-500 hover:bg-primary-600 hover:text-white' }}">
							<svg class="{{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/earnings') ? 'text-white' : 'text-gray-500 group-hover:text-white' }} h-6 w-6" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M12 7.5a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5z"></path><path fill-rule="evenodd" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 011.5 14.625v-9.75zM8.25 9.75a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM18.75 9a.75.75 0 00-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 00.75-.75V9.75a.75.75 0 00-.75-.75h-.008zM4.5 9.75A.75.75 0 015.25 9h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H5.25a.75.75 0 01-.75-.75V9.75z" clip-rule="evenodd"></path><path d="M2.25 18a.75.75 0 000 1.5c5.4 0 10.63.722 15.6 2.075 1.19.324 2.4-.558 2.4-1.82V18.75a.75.75 0 00-.75-.75H2.25z"></path></svg>
							<span class="mt-2">@lang('messages.t_earnings')</span>
						</a>

						{{-- Refunds --}}
						<a href="{{ url('seller/refunds') }}" class="group w-full p-3 rounded-md flex flex-col items-center text-xs font-medium {{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/refunds') ? 'bg-primary-600 text-white' : 'text-gray-500 hover:bg-primary-600 hover:text-white' }}">
							<svg class="{{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/refunds') ? 'text-white' : 'text-gray-500 group-hover:text-white' }} h-6 w-6" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12 1.5c-1.921 0-3.816.111-5.68.327-1.497.174-2.57 1.46-2.57 2.93V21.75a.75.75 0 001.029.696l3.471-1.388 3.472 1.388a.75.75 0 00.556 0l3.472-1.388 3.471 1.388a.75.75 0 001.029-.696V4.757c0-1.47-1.073-2.756-2.57-2.93A49.255 49.255 0 0012 1.5zm-.97 6.53a.75.75 0 10-1.06-1.06L7.72 9.22a.75.75 0 000 1.06l2.25 2.25a.75.75 0 101.06-1.06l-.97-.97h3.065a1.875 1.875 0 010 3.75H12a.75.75 0 000 1.5h1.125a3.375 3.375 0 100-6.75h-3.064l.97-.97z" clip-rule="evenodd"></path></svg>
							<span class="mt-2">@lang('messages.t_refunds')</span>
						</a>

						{{-- Portfolio --}}
						<a href="{{ url('seller/portfolio') }}" class="group w-full p-3 rounded-md flex flex-col items-center text-xs font-medium {{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/portfolio') ? 'bg-primary-600 text-white' : 'text-gray-500 hover:bg-primary-600 hover:text-white' }}">
							<svg class="{{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/portfolio') ? 'text-white' : 'text-gray-500 group-hover:text-white' }} h-6 w-6" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g><path fill="none" d="M0 0h24v24H0z"></path><path d="M20 5H4v14l9.292-9.294a1 1 0 0 1 1.414 0L20 15.01V5zM2 3.993A1 1 0 0 1 2.992 3h18.016c.548 0 .992.445.992.993v16.014a1 1 0 0 1-.992.993H2.992A.993.993 0 0 1 2 20.007V3.993zM8 11a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"></path></g></svg>
							<span class="mt-2">@lang('messages.t_portfolio')</span>
						</a>
						
					</div>

				</div>
			</div>
		
			{{-- Mobile sidebar --}}
			<div x-show="open" class="md:hidden" x-ref="dialog" aria-modal="true" style="display: none">
				<div class="fixed inset-0 z-40 flex">

					{{-- Backdrop --}}
					<div 
						x-show="open" 
						x-transition:enter="transition-opacity ease-linear duration-300" 
						x-transition:enter-start="opacity-0" 
						x-transition:enter-end="opacity-100" 
						x-transition:leave="transition-opacity ease-linear duration-300" 
						x-transition:leave-start="opacity-100" 
						x-transition:leave-end="opacity-0"
						class="fixed inset-0 bg-gray-600 bg-opacity-75" 
						@click="open = false" 
						aria-hidden="true"></div>
			
					{{-- Sidebar --}}
					<div 
						x-show="open" 
						x-transition:enter="transition ease-in-out duration-300 transform" x-transition:enter-start="-translate-x-full" 
						x-transition:enter-end="translate-x-0" 
						x-transition:leave="transition ease-in-out duration-300 transform" 
						x-transition:leave-start="translate-x-0" 
						x-transition:leave-end="-translate-x-full"
						class="relative max-w-xs w-full bg-white pt-5 pb-4 flex-1 flex flex-col">

						{{-- Close button --}}
						<div 
							x-show="open" 
							x-transition:enter="ease-in-out duration-300" 
							x-transition:enter-start="opacity-0" 
							x-transition:enter-end="opacity-100" 
							x-transition:leave="ease-in-out duration-300" 
							x-transition:leave-start="opacity-100" 
							x-transition:leave-end="opacity-0" 
							class="absolute top-1 ltr:right-0 rtl:left-0 ltr:-mr-14 rtl:-ml-14 p-1">
							<button type="button" class="h-12 w-12 rounded-full flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-white" @click="open = false">
								<svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
								<span class="sr-only">Close sidebar</span>
							</button>
						</div>
			
						{{-- Logo --}}
						<div class="flex-shrink-0 px-4 flex items-center">
							@if (Cookie::get('dark_mode') === 'enabled' && settings('general')->logo_dark)
								<a href="{{ url('/') }}" class="block ltr:sm:mr-6 rtl:sm:ml-6">
									<img width="150" height="{{ settings('appearance')->sizes['header_desktop_logo_height'] }}" src="{{ placeholder_img() }}" data-src="{{ src(settings('general')->logo_dark) }}" class="lazy" alt="{{ settings('general')->title }}" style="height: {{ settings('appearance')->sizes['header_desktop_logo_height'] }}px;width:auto">
								</a>
							@else
								<a href="{{ url('/') }}" class="block ltr:sm:mr-6 rtl:sm:ml-6">
									<img width="150" height="{{ settings('appearance')->sizes['header_desktop_logo_height'] }}" src="{{ placeholder_img() }}" data-src="{{ src(settings('general')->logo) }}" class="lazy" alt="{{ settings('general')->title }}" style="height: {{ settings('appearance')->sizes['header_desktop_logo_height'] }}px;width:auto">
								</a>
							@endif
						</div>

						{{-- Links --}}
						<div class="mt-5 flex-1 h-0 px-2 overflow-y-auto">
							<nav class="h-full flex flex-col">
								<div class="space-y-1">

									{{-- Home --}}
									<a href="{{ url('seller/home') }}" class="group w-full py-2 px-3 rounded-md flex items-center text-sm font-medium {{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/home') ? 'bg-primary-600 text-white' : 'text-zinc-500 hover:bg-primary-600 hover:text-white' }}">
										<svg class="ltr:mr-3 rtl:ml-3 h-5 w-5 {{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/home') ? 'text-white' : 'text-zinc-700 group-hover:text-white' }}" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z"></path><path d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.43z"></path></svg>
										<span>@lang('messages.t_home')</span>
									</a>

									{{-- Orders --}}
									<a href="{{ url('seller/orders') }}" class="group w-full py-2 px-3 rounded-md flex items-center text-xs font-medium {{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/orders') ? 'bg-primary-600 text-white' : 'text-zinc-500 hover:bg-primary-600 hover:text-white' }}">
										<svg class="{{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/orders') ? 'text-white' : 'text-zinc-700 group-hover:text-white' }} ltr:mr-3 rtl:ml-3 h-5 w-5" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.5 6v.75H5.513c-.96 0-1.764.724-1.865 1.679l-1.263 12A1.875 1.875 0 004.25 22.5h15.5a1.875 1.875 0 001.865-2.071l-1.263-12a1.875 1.875 0 00-1.865-1.679H16.5V6a4.5 4.5 0 10-9 0zM12 3a3 3 0 00-3 3v.75h6V6a3 3 0 00-3-3zm-3 8.25a3 3 0 106 0v-.75a.75.75 0 011.5 0v.75a4.5 4.5 0 11-9 0v-.75a.75.75 0 011.5 0v.75z" clip-rule="evenodd"></path></svg>
										<span>@lang('messages.t_orders')</span>
									</a>

									{{-- Gigs --}}
									<a href="{{ url('seller/gigs') }}" class="group w-full py-2 px-3 rounded-md flex items-center text-xs font-medium {{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/gigs') ? 'bg-primary-600 text-white' : 'text-zinc-500 hover:bg-primary-600 hover:text-white' }}">
										<svg class="{{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/gigs') ? 'text-white' : 'text-zinc-700 group-hover:text-white' }} ltr:mr-3 rtl:ml-3 h-5 w-5" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M5.223 2.25c-.497 0-.974.198-1.325.55l-1.3 1.298A3.75 3.75 0 007.5 9.75c.627.47 1.406.75 2.25.75.844 0 1.624-.28 2.25-.75.626.47 1.406.75 2.25.75.844 0 1.623-.28 2.25-.75a3.75 3.75 0 004.902-5.652l-1.3-1.299a1.875 1.875 0 00-1.325-.549H5.223z"></path><path fill-rule="evenodd" d="M3 20.25v-8.755c1.42.674 3.08.673 4.5 0A5.234 5.234 0 009.75 12c.804 0 1.568-.182 2.25-.506a5.234 5.234 0 002.25.506c.804 0 1.567-.182 2.25-.506 1.42.674 3.08.675 4.5.001v8.755h.75a.75.75 0 010 1.5H2.25a.75.75 0 010-1.5H3zm3-6a.75.75 0 01.75-.75h3a.75.75 0 01.75.75v3a.75.75 0 01-.75.75h-3a.75.75 0 01-.75-.75v-3zm8.25-.75a.75.75 0 00-.75.75v5.25c0 .414.336.75.75.75h3a.75.75 0 00.75-.75v-5.25a.75.75 0 00-.75-.75h-3z" clip-rule="evenodd"></path></svg>
										<span>@lang('messages.t_gigs')</span>
									</a>

									{{-- Payouts --}}
									<a href="{{ url('seller/withdrawals') }}" class="group w-full py-2 px-3 rounded-md flex items-center text-xs font-medium {{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/withdrawals') ? 'bg-primary-600 text-white' : 'text-zinc-500 hover:bg-primary-600 hover:text-white' }}">
										<svg class="{{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/withdrawals') ? 'text-white' : 'text-zinc-700 group-hover:text-white' }} ltr:mr-3 rtl:ml-3 h-5 w-5" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M4.5 3.75a3 3 0 00-3 3v.75h21v-.75a3 3 0 00-3-3h-15z"></path><path fill-rule="evenodd" d="M22.5 9.75h-21v7.5a3 3 0 003 3h15a3 3 0 003-3v-7.5zm-18 3.75a.75.75 0 01.75-.75h6a.75.75 0 010 1.5h-6a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5h3a.75.75 0 000-1.5h-3z" clip-rule="evenodd"></path></svg>
										<span>@lang('messages.t_payouts')</span>
									</a>

									{{-- Reviews --}}
									<a href="{{ url('seller/reviews') }}" class="group w-full py-2 px-3 rounded-md flex items-center text-xs font-medium {{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/reviews') ? 'bg-primary-600 text-white' : 'text-zinc-500 hover:bg-primary-600 hover:text-white' }}">
										<svg class="{{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/reviews') ? 'text-white' : 'text-zinc-700 group-hover:text-white' }} ltr:mr-3 rtl:ml-3 h-5 w-5" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"></path></svg>
										<span>@lang('messages.t_reviews')</span>
									</a>

									{{-- Earnings --}}
									<a href="{{ url('seller/earnings') }}" class="group w-full py-2 px-3 rounded-md flex items-center text-xs font-medium {{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/earnings') ? 'bg-primary-600 text-white' : 'text-zinc-500 hover:bg-primary-600 hover:text-white' }}">
										<svg class="{{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/earnings') ? 'text-white' : 'text-zinc-700 group-hover:text-white' }} ltr:mr-3 rtl:ml-3 h-5 w-5" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path d="M12 7.5a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5z"></path><path fill-rule="evenodd" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 011.5 14.625v-9.75zM8.25 9.75a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM18.75 9a.75.75 0 00-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 00.75-.75V9.75a.75.75 0 00-.75-.75h-.008zM4.5 9.75A.75.75 0 015.25 9h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H5.25a.75.75 0 01-.75-.75V9.75z" clip-rule="evenodd"></path><path d="M2.25 18a.75.75 0 000 1.5c5.4 0 10.63.722 15.6 2.075 1.19.324 2.4-.558 2.4-1.82V18.75a.75.75 0 00-.75-.75H2.25z"></path></svg>
										<span>@lang('messages.t_earnings')</span>
									</a>

									{{-- Refunds --}}
									<a href="{{ url('seller/refunds') }}" class="group w-full py-2 px-3 rounded-md flex items-center text-xs font-medium {{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/refunds') ? 'bg-primary-600 text-white' : 'text-zinc-500 hover:bg-primary-600 hover:text-white' }}">
										<svg class="{{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/refunds') ? 'text-white' : 'text-zinc-700 group-hover:text-white' }} ltr:mr-3 rtl:ml-3 h-5 w-5" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12 1.5c-1.921 0-3.816.111-5.68.327-1.497.174-2.57 1.46-2.57 2.93V21.75a.75.75 0 001.029.696l3.471-1.388 3.472 1.388a.75.75 0 00.556 0l3.472-1.388 3.471 1.388a.75.75 0 001.029-.696V4.757c0-1.47-1.073-2.756-2.57-2.93A49.255 49.255 0 0012 1.5zm-.97 6.53a.75.75 0 10-1.06-1.06L7.72 9.22a.75.75 0 000 1.06l2.25 2.25a.75.75 0 101.06-1.06l-.97-.97h3.065a1.875 1.875 0 010 3.75H12a.75.75 0 000 1.5h1.125a3.375 3.375 0 100-6.75h-3.064l.97-.97z" clip-rule="evenodd"></path></svg>
										<span>@lang('messages.t_refunds')</span>
									</a>

									{{-- Portfolio --}}
									<a href="{{ url('seller/portfolio') }}" class="group w-full py-2 px-3 rounded-md flex items-center text-xs font-medium {{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/portfolio') ? 'bg-primary-600 text-white' : 'text-zinc-500 hover:bg-primary-600 hover:text-white' }}">
										<svg class="{{ \Illuminate\Support\Str::of(request()->path())->startsWith('seller/portfolio') ? 'text-white' : 'text-zinc-700 group-hover:text-white' }} ltr:mr-3 rtl:ml-3 h-5 w-5" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g><path fill="none" d="M0 0h24v24H0z"></path><path d="M20 5H4v14l9.292-9.294a1 1 0 0 1 1.414 0L20 15.01V5zM2 3.993A1 1 0 0 1 2.992 3h18.016c.548 0 .992.445.992.993v16.014a1 1 0 0 1-.992.993H2.992A.993.993 0 0 1 2 20.007V3.993zM8 11a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"></path></g></svg>
										<span>@lang('messages.t_portfolio')</span>
									</a>

								</div>
							</nav>
						</div>

					</div>
			
					<div class="flex-shrink-0 w-14" aria-hidden="true"></div>
				</div>
			</div>
		
			{{-- Container --}}
			<div class="flex-1 flex flex-col overflow-hidden">

				{{-- Header --}}
				<header class="w-full">
					<div class="relative z-10 flex-shrink-0 h-16 bg-white border-b border-gray-200 shadow-sm flex">

						{{-- Mobile menu --}}
						<button type="button" class="px-4 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-primary-600 md:hidden" @click="open = true">
							<span class="sr-only">Open sidebar</span>
							<svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
						</button>

						<div class="flex-1 flex justify-between px-4 sm:px-6">
							<div class="flex-1 flex"></div>
							<div class="ltr:ml-2 rtl:mr-2 flex items-center space-x-4 rtl:space-x-reverse sm:ltr:ml-6 sm:rtl:mr-6 sm:space-x-6">

								{{-- Switch buying --}}
								<a href="{{ url('/') }}" class="text-primary-600 hover:text-primary-700 font-medium text-sm transition-colors duration-300 py-2 px-4 hidden lg:block dark:text-gray-100 dark:hover:text-white">
									@lang('messages.t_switch_to_buying')
								</a>

								{{-- Profile --}}
								<div class="relative flex-shrink-0" x-data="{ profile: false }">
									<div>
										<button x-on:click="profile = !profile" type="button" class="bg-white rounded-full flex text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500" :aria-expanded="profile" aria-haspopup="true">
											<img src="{{ placeholder_img() }}" data-src="{{ src(auth()->user()->avatar) }}" alt="{{ auth()->user()->username }}" class="lazy inline-block w-8 h-w-8 rounded-full">
										</button>
									</div>
					
									{{-- Menu --}}
									<div 
										x-show="profile" 
										style="display: none"
										x-transition:enter="transition ease-out duration-150" 
										x-transition:enter-start="transform opacity-0 scale-75" 
										x-transition:enter-end="transform opacity-100 scale-100" 
										x-transition:leave="transition ease-in duration-100" 
										x-transition:leave-start="transform opacity-100 scale-100" 
										x-transition:leave-end="transform opacity-0 scale-75" 
										x-on:click.outside="profile = false"
										class="absolute top-full ltr:right-0 rtl:left-0 w-60 mt-3 bg-white dark:bg-zinc-800 rounded-lg shadow-md ring-1 ring-gray-900 ring-opacity-5 font-normal text-sm text-gray-900 divide-y divide-gray-100 dark:divide-zinc-700 z-60">
										
											<p class="py-3 px-3.5 truncate">
												<span
													class="block mb-0.5 text-xs text-gray-500 dark:text-gray-300">{{ __('messages.t_logged_in_as_username', ['username' => auth()->user()->username]) }}</span>
												<span class="font-semibold dark:text-white">@money(auth()->user()->balance_available, settings('currency')->code, true)</span>
											</p>

											{{-- Seller --}}
											<div class="py-1.5 px-3.5">

												{{-- Buyer --}}
												@if (auth()->user()->account_type === 'buyer')
													{{-- Become a seller --}}
													<a href="{{ url('start_selling') }}"
														class="group flex items-center py-1.5 group-hover:text-primary-600">
														<svg xmlns="http://www.w3.org/2000/svg"
															class="flex-none ltr:mr-3 rtl:ml-3 text-gray-400 group-hover:text-primary-600 h-5 w-5"
															fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
															<path stroke-linecap="round" stroke-linejoin="round"
																d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
														</svg>
														<span
															class="font-semibold text-xs text-gray-700 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-500">{{ __('messages.t_become_a_seller') }}</span>
													</a>
												@endif

												{{-- Buyer --}}
												@if (auth()->user()->account_type === 'seller')
													{{-- Seller dashboard --}}
													<a href="{{ url('seller/home') }}"
														class="group flex items-center py-1.5 group-hover:text-primary-600">
														<svg xmlns="http://www.w3.org/2000/svg"
															class="flex-none ltr:mr-3 rtl:ml-3 text-gray-400 group-hover:text-primary-600 h-5 w-5"
															fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
															<path stroke-linecap="round" stroke-linejoin="round"
																d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
														</svg>
														<span
															class="font-semibold text-xs text-gray-700 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-500">{{ __('messages.t_seller_dashboard') }}</span>
													</a>
												@endif

											</div>

											{{-- Project --}}
											@if (settings('projects')->is_enabled)
												<div class="py-1.5 px-3.5">

													{{-- My projects --}}
													<a href="{{ url('account/projects') }}" class="group flex items-center py-1.5 group-hover:text-primary-600">
														<svg xmlns="http://www.w3.org/2000/svg" class="flex-none ltr:mr-3 rtl:ml-3 text-gray-400 group-hover:text-primary-600 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
														<span class="font-semibold text-xs text-gray-700 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-500">
															{{ __('messages.t_my_projects') }}
														</span>
													</a>

													{{-- Post a project --}}
													<a href="{{ url('post/project') }}" class="group flex items-center py-1.5 group-hover:text-primary-600">
														<svg xmlns="http://www.w3.org/2000/svg" class="flex-none ltr:mr-3 rtl:ml-3 text-gray-400 group-hover:text-primary-600 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
														<span class="font-semibold text-xs text-gray-700 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-500">
															{{ __('messages.t_post_project') }}
														</span>
													</a>

												</div>
											@endif

											{{-- Account --}}
											<div class="py-1.5 px-3.5">

												{{-- View Profile --}}
												<a href="{{ url('profile', auth()->user()->username) }}"
													class="group flex items-center py-1.5 group-hover:text-primary-600">
													<svg xmlns="http://www.w3.org/2000/svg"
														class="flex-none ltr:mr-3 rtl:ml-3 text-gray-400 group-hover:text-primary-600 h-5 w-5"
														fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
														<path stroke-linecap="round" stroke-linejoin="round"
															d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
													</svg>
													<span
														class="font-semibold text-xs text-gray-700 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-500">{{ __('messages.t_view_profile') }}</span>
												</a>

												{{-- Edit profile --}}
												<a href="{{ url('account/profile') }}"
													class="group flex items-center py-1.5 group-hover:text-primary-600">
													<svg xmlns="http://www.w3.org/2000/svg"
														class="flex-none ltr:mr-3 rtl:ml-3 text-gray-400 group-hover:text-primary-600 h-5 w-5"
														fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
														<path stroke-linecap="round" stroke-linejoin="round"
															d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
													</svg>
													<span
														class="font-semibold text-xs text-gray-700 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-500">{{ __('messages.t_edit_profile') }}</span>
												</a>

												{{-- Account settings --}}
												<a href="{{ url('account/settings') }}"
													class="group flex items-center py-1.5 group-hover:text-primary-600">
													<svg xmlns="http://www.w3.org/2000/svg"
														class="flex-none ltr:mr-3 rtl:ml-3 text-gray-400 group-hover:text-primary-600 h-5 w-5"
														fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
														<path stroke-linecap="round" stroke-linejoin="round"
															d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
														<path stroke-linecap="round" stroke-linejoin="round"
															d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
													</svg>
													<span
														class="font-semibold text-xs text-gray-700 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-500">{{ __('messages.t_account_settings') }}</span>
												</a>

												{{-- Update password --}}
												<a href="{{ url('account/password') }}"
													class="group flex items-center py-1.5 group-hover:text-primary-600">
													<svg xmlns="http://www.w3.org/2000/svg" class="flex-none ltr:mr-3 rtl:ml-3 text-gray-400 group-hover:text-primary-600 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
													<span class="font-semibold text-xs text-gray-700 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-500">{{ __('messages.t_update_password') }}</span>
												</a>

											</div>

											{{-- Content --}}
											<div class="py-1.5 px-3.5">

												{{-- Deposit --}}
												<a href="{{ url('account/deposit') }}" class="group flex items-center py-1.5 group-hover:text-primary-600">
													<svg xmlns="http://www.w3.org/2000/svg" class="flex-none ltr:mr-3 rtl:ml-3 text-gray-400 group-hover:text-primary-600 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
													<span class="font-semibold text-xs text-gray-700 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-500">{{ __('messages.t_deposit') }}</span>
												</a>

												{{-- My orders --}}
												<a href="{{ url('account/orders') }}"
													class="group flex items-center py-1.5 group-hover:text-primary-600">
													<svg xmlns="http://www.w3.org/2000/svg"
														class="flex-none ltr:mr-3 rtl:ml-3 text-gray-400 group-hover:text-primary-600 h-5 w-5"
														fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
														<path stroke-linecap="round" stroke-linejoin="round"
															d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
													</svg>
													<span
														class="font-semibold text-xs text-gray-700 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-500">{{ __('messages.t_my_orders') }}</span>
												</a>

												{{-- Messages --}}
												<a href="{{ url('messages') }}"
													class="group flex items-center py-1.5 group-hover:text-primary-600">
													<svg xmlns="http://www.w3.org/2000/svg"
														class="flex-none ltr:mr-3 rtl:ml-3 text-gray-400 group-hover:text-primary-600 h-5 w-5"
														fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
														<path stroke-linecap="round" stroke-linejoin="round"
															d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
													</svg>
													<span
														class="font-semibold text-xs text-gray-700 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-500">{{ __('messages.t_messages') }}</span>
												</a>

												{{-- Reviews --}}
												<a href="{{ url('account/reviews') }}" class="group flex items-center py-1.5 group-hover:text-primary-600">
													<svg xmlns="http://www.w3.org/2000/svg" class="flex-none ltr:mr-3 rtl:ml-3 text-gray-400 group-hover:text-primary-600 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
													<span class="font-semibold text-xs text-gray-700 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-500">{{ __('messages.t_reviews') }}</span>
												</a>

												{{-- Refunds --}}
												<a href="{{ url('account/refunds') }}" class="group flex items-center py-1.5 group-hover:text-primary-600">
													<svg xmlns="http://www.w3.org/2000/svg" class="flex-none ltr:mr-3 rtl:ml-3 text-gray-400 group-hover:text-primary-600 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"> <path stroke-linecap="round" stroke-linejoin="round" d="M16 15v-1a4 4 0 00-4-4H8m0 0l3 3m-3-3l3-3m9 14V5a2 2 0 00-2-2H6a2 2 0 00-2 2v16l4-2 4 2 4-2 4 2z"/></svg>
													<span class="font-semibold text-xs text-gray-700 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-500">{{ __('messages.t_refunds') }}</span>
												</a>

											</div>

											{{-- Security --}}
											<div class="py-1.5 px-3.5">

												{{-- Verification center --}}
												@if (auth()->user()->status !== 'verified')
													<a href="{{ url('account/verification') }}"
														class="group flex items-center py-1.5 group-hover:text-primary-600">
														<svg xmlns="http://www.w3.org/2000/svg"
															class="flex-none ltr:mr-3 rtl:ml-3 text-gray-400 group-hover:text-primary-600 h-5 w-5"
															fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
															<path stroke-linecap="round" stroke-linejoin="round"
																d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
														</svg>
														<span
															class="font-semibold text-xs text-gray-700 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-500">{{ __('messages.t_verification_center') }}</span>
													</a>
												@endif

												{{-- Logout --}}
												<a href="{{ url('auth/logout') }}"
													class="group flex items-center py-1.5 group-hover:text-primary-600">
													<svg aria-hidden="true" width="20" height="20" fill="none"
														class="flex-none ltr:mr-3 rtl:ml-3 text-gray-400 group-hover:text-primary-600 h-5 w-5">
														<path
															d="M10.25 3.75H9A6.25 6.25 0 002.75 10v0A6.25 6.25 0 009 16.25h1.25M10.75 10h6.5M14.75 12.25l2.5-2.25-2.5-2.25"
															stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
															stroke-linejoin="round" />
													</svg>
													<span
														class="font-semibold text-xs text-gray-700 dark:text-gray-100 group-hover:text-primary-600 dark:group-hover:text-primary-500">{{ __('messages.t_logout') }}</span>
												</a>

											</div>

									</div>
									
								</div>
				
								{{-- Create new gig --}}
								<a href="{{ url('create') }}" class="flex bg-primary-600 p-1 rounded-full items-center justify-center text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
									<svg class="h-6 w-6" stroke="currentColor" fill="none" stroke-width="0" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
									<span class="sr-only">@lang('messages.t_create_a_gig')</span>
								</a>

							</div>
						</div>

					</div>
				</header>
		
				{{-- Content --}}
				<div class="flex-1 flex items-stretch overflow-hidden">
					<main class="flex-1 overflow-y-auto px-6 lg:px-16 py-8 lg:py-12">
						@yield('content')
					</main>
				</div>

			</div>

		</div>

		{{-- Livewire scripts --}}
		@livewireScripts

		{{-- Wire UI --}}
		<wireui:scripts />

		{{-- Core --}}
		<script defer src="{{ mix('js/app.js') }}"></script>

		{{-- Helpers --}}
		<script defer src="{{ url('public/js/utils.js') }}"></script>

		{{-- Custom JS codes --}}
		<script defer>
			
			document.addEventListener("DOMContentLoaded", function(){

				jQuery.event.special.touchstart = {
					setup: function( _, ns, handle ) {
						this.addEventListener("touchstart", handle, { passive: !ns.includes("noPreventDefault") });
					}
				};
				jQuery.event.special.touchmove = {
					setup: function( _, ns, handle ) {
						this.addEventListener("touchmove", handle, { passive: !ns.includes("noPreventDefault") });
					}
				};
				jQuery.event.special.wheel = {
					setup: function( _, ns, handle ){
						this.addEventListener("wheel", handle, { passive: true });
					}
				};
				jQuery.event.special.mousewheel = {
					setup: function( _, ns, handle ){
						this.addEventListener("mousewheel", handle, { passive: true });
					}
				};

				// Refresh
				window.addEventListener('refresh',() => {
					location.reload();
				});

				// Scroll to specific div
				window.addEventListener('scrollTo', (event) => {

					// Get id to scroll
					const id = event.detail;

					// Scroll
					$('html, body').animate({
						scrollTop: $("#" + id).offset().top
					}, 500);

				});

				// Scroll to up page
				window.addEventListener('scrollUp', () => {

					// Scroll
					$('html, body').animate({
						scrollTop: $("body").offset().top
					}, 500);

				});

			});

			function jwUBiFxmwbrUwww() {
				return {

					scroll: false,

					init() {
						var _this = this;
						$(document).scroll(function () {
							$(this).scrollTop() > 70 ? _this.scroll = true : _this.scroll = false;
						});

					}

				}
			}
			window.jwUBiFxmwbrUwww = jwUBiFxmwbrUwww();

			document.ontouchmove = function(event){
				event.preventDefault();
			}
			
		</script>

		{{-- Custom scripts --}}
		@stack('scripts')

	</body>
</html>
  