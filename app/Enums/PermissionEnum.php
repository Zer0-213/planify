<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case INVITE_USER = 'create_user';
    case CREATE_SHIFTS = 'create_shifts';
    case VIEW_SHIFTS = 'view_shifts';
    case ASSIGN_SHIFT = 'assign_shift';
    case MANAGE_COMPANY = 'manage_company';
}
