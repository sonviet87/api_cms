<?php namespace App\Constants;

class UserConst
{
    const STATUS_UNACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const STATUS_NAME = [
        UserConst::STATUS_UNACTIVE => 'UnActive',
        UserConst::STATUS_ACTIVE => 'Active',
    ];
}
