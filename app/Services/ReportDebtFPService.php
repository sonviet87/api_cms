<?php

namespace App\Services;



use App\Constants\RolePermissionConst;
use App\Interfaces\ReportDebtFPInterface;
use Illuminate\Support\Facades\Auth;


class ReportDebtFPService extends BaseService
{
    protected $report;

    function __construct(ReportDebtFPInterface $report)
    {
        $this->report = $report;

    }

    public function getListPaginate($perPage = 20, $filter)
    {
        
        return $this->report->getListPaginate($perPage, $filter);

    }


}
