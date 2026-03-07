@props([
    'name',
    'show' => false,
    'maxWidth' => '2xl'
])

@php
$maxWidth = [
    'sm' => 'sm:max-w-sm',
    'md' => 'sm:max-w-md',
    'lg' => 'sm:max-w-lg',
    'xl' => 'sm:max-w-xl',
    '2xl' => 'sm:max-w-2xl',
][$maxWidth];
@endphp

<div id="{{ $name }}" class="modal-wrapper fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0 flex items-center justify-center {{ $show ? '' : 'hidden pointer-events-none' }}">
    <div class="fixed inset-0 transform transition-all">
        <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75"></div>
    </div>

    <div class="mb-6 bg-white dark:bg-gray-800 rounded-none overflow-hidden shadow-xl transform transition-all sm:w-full {{ $maxWidth }} sm:mx-auto relative z-10">
        {{ $slot }}
    </div>
</div>

<style>
    .modal-wrapper:target {
        display: flex !important;
        pointer-events: auto !important;
    }
</style>
