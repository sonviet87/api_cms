<?php namespace App\Constants;

class FPConst
{
    const STATUS_NEW = 0;
    const STATUS_PAKD = 1;
    const STATUS_PAKD_FAILED = 2;
    const STATUS_CONTRACT = 3;
    const STATUS_SHIPPING = 4;
    const STATUS_INVOICE = 5;

    const STATUS_COMPLETED = 6;
    const STATUS_BACK = 7;

    const STATUS_NAME = [
        FPConst::STATUS_NEW => 'Mới',
        //'Duyệt PAKD'
        FPConst::STATUS_PAKD => 'Duyệt giá Sale',
        FPConst::STATUS_PAKD_FAILED => 'Hủy PAKD',
        //'Duyệt PAKD'
        FPConst::STATUS_CONTRACT => 'Duyệt giá bán',
        //'Duyệt giao hàng'
        FPConst::STATUS_SHIPPING => 'Duyệt PA Triển Khai ',
        FPConst::STATUS_INVOICE => 'Xuất hóa đơn',

        FPConst::STATUS_COMPLETED => 'Hoàn tất hợp đồng',
        FPConst::STATUS_BACK => 'Trả về',
    ];
}
