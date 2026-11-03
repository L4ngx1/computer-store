<x-mail::message>
{{-- Lời chào --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# Xin lỗi!
@else
# Xin chào!
@endif
@endif

{{-- Các dòng mở đầu --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Nút hành động --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset

{{-- Các dòng kết --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Lời chào cuối --}}
@if (! empty($salutation))
{{ $salutation }}
@else
Trân trọng,<br>
{{ config('app.name') }}
@endif

{{-- Ghi chú URL --}}
@isset($actionText)
<x-slot:subcopy>
Nếu bạn gặp khó khăn khi nhấn vào nút "{{ $actionText }}", hãy sao chép và dán địa chỉ bên dưới
vào trình duyệt của bạn: <span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
