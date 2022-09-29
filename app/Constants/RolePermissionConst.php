<?php namespace App\Constants;

class RolePermissionConst
{
    const ROLE_ADMIN = 1;
    const ROLE_AGENCY = 2;
    const ROLE_CLIENT = 3;

    const STATUS_NAME = [
        RolePermissionConst::ROLE_ADMIN => 'Admin',
        RolePermissionConst::ROLE_AGENCY => 'Agency',
        RolePermissionConst::ROLE_CLIENT => 'Client',
    ];
}
