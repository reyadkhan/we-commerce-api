@component('mail::message')
# {{ $notification->title }}

{{ $notification->details }}

@component('mail::button', ['url' => $url])
    View Order
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
