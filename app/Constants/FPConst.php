<?php namespace App\Constants;

class FPConst
{
    const STATUS_NEW = 0;
    const STATUS_PAKD = 1;
    const STATUS_PAKD_FAILED = 2;
    const STATUS_CONTRACT = 3;
    const STATUS_SHIPPING = 4;
    const STATUS_INVOICE = 5;
    const STATUS_INVOICE_FAILED = 6;
    const STATUS_COMPLETED = 7;
    const STATUS_BACK = 8;

    const STATUS_NAME = [
        FPConst::STATUS_NEW => 'Mới',
        FPConst::STATUS_PAKD => 'Duyệt PAKD',
        FPConst::STATUS_PAKD_FAILED => 'Hủy PAKD',
        FPConst::STATUS_CONTRACT => 'Duyệt hợp đồng',
        FPConst::STATUS_SHIPPING => 'Duyệt giao hàng',
        FPConst::STATUS_INVOICE => 'Xuất hóa đơn',
        FPConst::STATUS_INVOICE_FAILED => 'Hủy hóa đơn',
        FPConst::STATUS_COMPLETED => 'Hoàn tất hợp đồng',
        FPConst::STATUS_BACK => 'Trả về',
    ];
}
