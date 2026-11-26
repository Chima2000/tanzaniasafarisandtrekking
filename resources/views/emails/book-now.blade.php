<x-mail::message>
# New Book Now request

**Name:** {{ $payload['full_name'] }}  
**Email:** {{ $payload['email'] }}  
@if (! empty($payload['phone']))
**Phone:** {{ $payload['phone'] }}  
@endif

@foreach ($payload['lines'] as $line)
@if ($line === '')

@else
{{ $line }}  
@endif
@endforeach

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
