@php
    $user = $timeOffRequest->companyUser->user;
    $company = $timeOffRequest->companyUser->company;
    $approverName = $timeOffRequest->approver?->user?->name ?? 'Administrator';
@endphp

Hello {{ $user->name }},

Your time off request from {{ $timeOffRequest->start_date }} to {{ $timeOffRequest->end_date }} has been {{ ucfirst($timeOffRequest->status) }}.

Responded by: {{ $approverName }}

@if ($timeOffRequest->admin_notes)
    Admin Notes: {{ $timeOffRequest->admin_notes }}
@endif

Kind regards,
{{ $company->name }}
