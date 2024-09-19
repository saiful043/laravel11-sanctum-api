@component('mail::message')

Hi, {{$user->name}}. Forgot Password?

<p>It Happpens.</p>

{{-- @component('mail::button', ['url' => url('api/reset/'. $user->remember_token)]) --}}
@component('mail::button', ['url' => config('app.frontend_url').'/reset_password/'. $user->remember_token])
Reset your Password 
@endcomponent

Thanks, <br>
{{config('app.name')}}
@endcomponent