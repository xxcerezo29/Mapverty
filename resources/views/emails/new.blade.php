<x-mail::message>
    Greetings {{ $name }}!
    <br>
    <br>
    You've been added as a User of {{ config('app.name') }}!
    <br>
    Please use the following Generated Password to login to your account.
    <p>Password: <strong>{{ $password }}</strong></p>
    Please Change your password after you login.
    <br>
    <x-mail::button url="{{route('login')}}">Login</x-mail::button>
    Do not share this OTP with anyone. MaPverty takes yours information security very seriously.
    MaPverty will never ask you to disclose or verify your OTP. If you receive a suspicious email
    with a link to update your personal information, do not click on the link---instead,
    report the email to MaPverty for investigation.
    <br>
    <br>
    Thanks,
    <br>
    {{ config('app.name') }}
</x-mail::message>


