<x-mail::message>
    Greetings from {{ config('app.name') }}!
    <br>
    <br>
    To verify your email address, please use the following One Time Password
    <br>
    <br>
    One Time Password: <strong>{{ $otp }}</strong>
    <br>
    <br>
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
