<x-mail::message>
    greetings from {{ config('app.name') }}!
    You've been added as a User of {{ config('app.name') }}!
    Please use the following Generated Password to login to your account.

    {{ $password }}

    Please Change your password after you login.

    Do not share this OTP with anyone. MaPverty takes yours information security very seriously.
    MaPverty will never ask you to disclose or verify your OTP. If you receive a suspicious email
    with a link to update your personal information, do not click on the link---insteads,
    report the email to MaPverty for investigation.

    Thanks,<br>
    {{ config('app.name') }}
</x-mail::message>
