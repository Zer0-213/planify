@php use App\Models\CompanyInvite;use Carbon\Carbon;@endphp

<?php
/**
 * @var CompanyInvite $companyInvite
 * @var string $token
 */
?>

@component('mail::message')
    # You're Invited!

    Hello,

    {{ $companyInvite->inviter?->name ?? 'Someone' }} has invited you to join {{$companyInvite->company?->name}} on our platform.

    @component('mail::button', ['url' => url("/accept-invite?token=$token&invite_id=$companyInvite->id")])
        Accept Invitation
    @endcomponent

    This invitation will expire in {{ Carbon::parse($companyInvite->expires_at)->diffForHumans() }}.

    Thanks,
    {{ config('app.name') }}
@endcomponent
