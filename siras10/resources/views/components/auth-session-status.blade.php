@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'font-semibold text-sm']) }} style="color: #166534 !important;">
        {{ $status }}
    </div>
@endif
