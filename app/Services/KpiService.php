<?php

namespace App\Services;



use App\Constants\RolePermissionConst;
use App\Interfaces\KpiInterface;
use App\Interfaces\ReportInterface;
use Illuminate\Support\Facades\Auth;


class KpiService extends BaseService
{
    protected $kpi;

    function __construct(KpiInterface $kpi)
    {
        $this->kpi = $kpi;

    }

    public function getList($filter)
    {
        return $this->kpi->getList($filter);
    }



}
