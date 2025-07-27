<?php

namespace App\Enums;

enum PermissionEnum: string
{
    case INVITE_USER = 'create_user';
    case CREATE_SHIFTS = 'create_shifts';
    case VIEW_SHIFTS = 'view_shifts';
    case ASSIGN_SHIFT = 'assign_shift';
    case MANAGE_COMPANY = 'manage_company';
    case VIEW_ALL_WAGES = 'view_all_wages';
    case DELETE_STAFF_MEMBER = 'delete_staff_member';
    case UPDATE_STAFF_MEMBER = 'update_staff_member';
    case VIEW_TIME_OFF_REQUESTS = 'view_time_off_requests';
    case MANAGE_TIME_OFF_REQUESTS = 'manage_time_off_requests';
    case REQUEST_TIME_OFF = 'request_time_off';

}
