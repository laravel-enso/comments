@component('mail::message')
{{ __('Hi :appellative', ['appellative' => $appellative]) }},

{{ __("You were just tagged in a comment:") }}

@component('mail::panel')
{{ $body }}
@endcomponent

{{ __('To view the full conversation click the button below.') }}

@component('mail::button', ['url' => $url, 'color' => 'green'])
{{ __('View conversation') }}
@endcomponent

{{ __('Thank you') }},<br>
{{ __(config('app.name')) }}
@endcomponent
