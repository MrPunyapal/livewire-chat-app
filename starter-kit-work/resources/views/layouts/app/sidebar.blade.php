<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky collapsible class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate tooltip="Dashboard">
                    {{ __('Dashboard') }}
                </flux:sidebar.item>

                <flux:sidebar.item icon="chat-bubble-left-right" :href="route('chats')" :current="request()->routeIs('chats')" wire:navigate tooltip="Chats">
                    {{ __('Chats') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>

            <flux:spacer />

            <div x-data="{ isChats: window.location.pathname.startsWith('/chats') }"
                 x-on:livewire:navigated.window="isChats = window.location.pathname.startsWith('/chats')"
                 x-show="!isChats"
            >
                <flux:sidebar.nav>
                    <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                        {{ __('Repository') }}
                    </flux:sidebar.item>

                    <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                        {{ __('Documentation') }}
                    </flux:sidebar.item>
                </flux:sidebar.nav>
            </div>

            <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <flux:avatar
                                    :name="auth()->user()->name"
                                    :initials="auth()->user()->initials()"
                                />

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                    <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile')" icon="cog" wire:navigate>
                            {{ __('Profile') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item
                            as="button"
                            type="submit"
                            icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer"
                            data-test="logout-button"
                        >
                            {{ __('Log out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        <script>
            document.addEventListener('livewire:navigated', function () {
                const sidebar = document.querySelector('[data-flux-sidebar]');
                if (!sidebar) return;

                const isChats = window.location.pathname.startsWith('/chats');
                const isCollapsed = sidebar.hasAttribute('data-flux-sidebar-collapsed-desktop');

                if (isChats && !isCollapsed) {
                    window.dispatchEvent(new CustomEvent('flux-sidebar-toggle'));
                } else if (!isChats && isCollapsed) {
                    window.dispatchEvent(new CustomEvent('flux-sidebar-toggle'));
                }
            });
        </script>

        @fluxScripts
    </body>
</html>
