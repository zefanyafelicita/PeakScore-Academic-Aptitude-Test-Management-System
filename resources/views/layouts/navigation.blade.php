{{-- 1. PRIMARY NAVIGATION (Desktop View) --}}
<nav x-data="{ open: false }" class="bg-white border-b border-slate-200/80 shadow-md shadow-slate-100/80 relative z-10">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- LEFT SIDE: Logo & Main Navigation Links --}}
            <div class="flex">
                {{-- PeakScore Logo Integration --}}
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <img src="{{ asset('images/logo.svg') }}" alt="PeakScore Logo" class="block h-9 w-auto object-contain">
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('questions.index')" :active="request()->routeIs('questions.*')">
                        {{ __('TPA Questions') }}
                    </x-nav-link>

                    {{-- Admin-only link --}}
                    @if(Auth::user()->is_admin)
                        <x-nav-link :href="route('test-results.index')" :active="request()->routeIs('test-results.*')">
                            {{ __('Test Results') }}
                        </x-nav-link>

                        <x-nav-link :href="route('participants.index')" :active="request()->routeIs('participants.*')">
                            {{ __('Participants') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            {{-- RIGHT SIDE: User Settings Dropdown (Optimized hover status for premium feel) --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-semibold rounded-md text-slate-600 bg-white hover:text-slate-900 focus:text-slate-900 transition duration-150 ease-in-out">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 transition-transform duration-150" :class="{'rotate-180': open}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        {{-- Logout link --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- HAMBURGER BUTTON (Mobile Menu Toggle) --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-2 rounded-md text-slate-500 hover:text-slate-800 focus:bg-slate-50 transition duration-150">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- 2. RESPONSIVE NAVIGATION (Mobile Menu) --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('questions.index')" :active="request()->routeIs('questions.*')">
                {{ __('TPA Questions') }}
            </x-responsive-nav-link>
        </div>

        {{-- Mobile Admin Links --}}
        @if(Auth::user()->is_admin)
            <div class="pt-2 pb-3 border-t border-slate-200 space-y-1">
                <x-responsive-nav-link :href="route('test-results.index')" :active="request()->routeIs('test-results.*')">
                    {{ __('Test Results') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('participants.index')" :active="request()->routeIs('participants.*')">
                    {{ __('Participants') }}
                </x-responsive-nav-link>
            </div>
        @endif

        {{-- Mobile User Settings --}}
        <div class="pt-4 pb-1 border-t border-slate-200">
            <div class="px-4">
                <div class="font-bold text-base text-slate-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-slate-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                {{-- Logout link --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>

</nav>
