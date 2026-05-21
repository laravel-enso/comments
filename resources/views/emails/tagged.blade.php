@component('mail::message')
@component('mail::title')
{{ __('Comment Tag Notification') }}
@endcomponent

{{ __('Hi :appellative', ['appellative' => $appellative]) }},

{{ __("You were just tagged in a comment:") }}

@component('mail::quote')
{{ $body }}
@endcomponent

{{ __('To view the full conversation click the button below.') }}

@component('mail::button', ['url' => $url, 'color' => 'blue'])
{{ __('View conversation') }}
@endcomponent

@component('mail::signature')
@endcomponent
@endcomponent
