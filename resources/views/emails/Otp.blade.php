@component('mail::message')
    greetings from {{ config('app.name') }}!
    {{--    Greetings from Vincent!--}}

    To verify your email address, please use the following One Time Password

    here the Verification Code: <strong>{{ $otp }}</strong>

    Do not share this OTP with anyone. MaPverty takes yours information security very seriously.
    MaPverty will never ask you to disclose or verify your OTP. If you receive a suspicious email
    with a link to update your personal information, do not click on the link---insteads,
    report the email to MaPverty for investigation.

    Thank you!

    {{--    Wag ka mabahala, tandaan mo lang ang code na ito: Maganda ka, lalo na cute ka--}}



    {{--    byessss!!!!--}}

@endcomponent
