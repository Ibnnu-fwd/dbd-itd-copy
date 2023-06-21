@props(['active' => false, 'title' => '', 'icon' => '', 'toggle' => ''])
<li>
    <button type="button"
        class="flex w-full items-center px-3 py-3 text-xs 2xl:text-sm text-gray-900 hover:bg-gray-100 {{ $active ? 'bg-gray-200' : '' }}"
        aria-controls="{{ $toggle }}" data-collapse-toggle="{{ $toggle }}">
        <i
            class="w-3 h-3 {{ $active ? 'text-primary' : 'text-gray-500' }} transition duration-75 {{ $icon }}"></i>
        <span class="flex-1 ml-2 text-left whitespace-nowrap" sidebar-toggle-item>
            {{ $title }}
        </span>
        <i class="fas {{ $active ? 'fa-chevron-up' : 'fa-chevron-down' }} ml-auto"></i>
    </button>
    <ul id="{{ $toggle }}" class="{{ $active ? 'block' : 'hidden' }}">
        {{ $slot }}
    </ul>
</li>
