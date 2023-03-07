<?php

namespace App\Services;



use App\Constants\RolePermissionConst;
use App\Interfaces\ReportInterface;
use Illuminate\Support\Facades\Auth;


class ReportService extends BaseService
{
    protected $report;

    function __construct(ReportInterface $report)
    {
        $this->report = $report;

    }

    public function getListPaginate($perPage = 20, $filter)
    {
        $role = Auth::user()->roles->pluck('name')->first();
        if(!$role) return $this->_result(false, "Không tìm thấy user");
        if($role == RolePermissionConst::STATUS_NAME[RolePermissionConst::ROLE_SALE]){
            $filter['user_id'] = Auth::user()->id;
        }
        return $this->report->getListPaginate($perPage, $filter);

    }



}
