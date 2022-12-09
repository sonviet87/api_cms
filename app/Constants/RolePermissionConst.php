<?php namespace App\Constants;

class RolePermissionConst
{
    const ROLE_ADMIN = 1;
    const ROLE_CEO = 2;
    const ROLE_Manager = 3;
    const ROLE_SALE = 4;

    const STATUS_NAME = [
        RolePermissionConst::ROLE_ADMIN => 'Admin',
        RolePermissionConst::ROLE_CEO => 'CEO',
        RolePermissionConst::ROLE_Manager => 'Manager',
        RolePermissionConst::ROLE_SALE => 'Sale',
    ];
}
