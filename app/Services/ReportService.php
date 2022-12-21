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

        return $this->report->getListPaginate($perPage, $filter);

    }


}
