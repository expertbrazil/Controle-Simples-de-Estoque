@props(['type' => 'info', 'message', 'dismissible' => true])

@php
    $classes = [
        'success' => 'alert-success',
        'danger' => 'alert-danger',
        'warning' => 'alert-warning',
        'info' => 'alert-info'
    ];
    
    $icons = [
        'success' => 'bi bi-check-circle',
        'danger' => 'bi bi-exclamation-circle',
        'warning' => 'bi bi-exclamation-triangle',
        'info' => 'bi bi-info-circle'
    ];
@endphp

<div {{ $attributes->merge(['class' => 'alert ' . $classes[$type] . ($dismissible ? ' alert-dismissible fade show' : '')]) }} 
     role="alert">
    <i class="{{ $icons[$type] }} me-2"></i>
    {{ $message }}
    @if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    @endif
</div>
