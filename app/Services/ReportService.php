<?php

namespace App\Services;



use App\Constants\PermissionConst;
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
        $user = Auth::user();
        if(!$user) return $this->_result(false, "Không tìm thấy user");

        if ($user->hasPermissionTo(PermissionConst::IS_SALE)) {
            $filter['user_id'] = [$user->id];

        }
        return $this->report->getListPaginate($perPage, $filter);

    }



}
