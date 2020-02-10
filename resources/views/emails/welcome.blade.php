@component('mail::message')
# Hello {{$user->name}}

Thank you for create an account. Please verify your email using the <button type="reset"></button>:

@component('mail::button', ['url' => route('verify', $user->verification_token) ])
Verify Account
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent











