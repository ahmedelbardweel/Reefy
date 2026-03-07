@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1 bg-white dark:bg-gray-800'])

@php
switch ($align) {
    case 'left':
        $alignmentClasses = 'ltr:origin-top-left rtl:origin-top-right start-0';
        break;
    case 'top':
        $alignmentClasses = 'origin-top';
        break;
    case 'right':
    default:
        $alignmentClasses = 'ltr:origin-top-right rtl:origin-top-left end-0';
        break;
}

switch ($width) {
    case '48':
        $width = 'w-48';
        break;
}

// Generate a unique ID for the checkbox to avoid collisions
$id = 'dropdown_' . str_replace('.', '_', uniqid('', true));
@endphp

<div class="relative inline-block text-right">
    <input type="checkbox" id="{{ $id }}" class="peer hidden">
    
    <label for="{{ $id }}" class="cursor-pointer">
        {{ $trigger }}
    </label>

    <!-- Overlay to close on click outside (simulated by a full-screen label) -->
    <label for="{{ $id }}" class="fixed inset-0 z-40 hidden peer-checked:block cursor-default"></label>

    <div class="absolute z-50 mt-2 {{ $width }} rounded-none shadow-lg {{ $alignmentClasses }} hidden peer-checked:block">
        <div class="rounded-none ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>

<style>
    /* CSS-only dropdown behavior */
    .peer:checked ~ .dropdown-menu {
        display: block;
    }
</style>
