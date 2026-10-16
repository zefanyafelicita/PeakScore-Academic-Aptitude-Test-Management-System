@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-2 border-b-2 border-[#1c1f23] text-sm font-bold leading-5 text-slate-900 focus:outline-none focus:border-[#1c1f23] transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-2 border-b-2 border-transparent text-sm font-medium leading-5 text-slate-500 hover:text-slate-800 hover:border-slate-300 focus:outline-none focus:text-slate-700 focus:border-slate-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
