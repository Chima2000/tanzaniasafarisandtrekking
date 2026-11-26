<x-mail::message>
# New contact submission

**Name:** {{ $payload['full_name'] }}  
**Email:** {{ $payload['email'] }}  
@if (! empty($payload['phone']))
**Phone:** {{ $payload['phone'] }}  
@endif
@if (! empty($payload['subject']))
**Subject:** {{ $payload['subject'] }}  
@endif
@if (! empty($payload['package_name']))
**Package:** {{ $payload['package_name'] }}  
@endif
@if (! empty($payload['interest']))
**Interest:** {{ $payload['interest'] }}  
@endif
**Source:** {{ $payload['source'] }}  

@if (! empty($payload['message']))
{{ $payload['message'] }}
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
