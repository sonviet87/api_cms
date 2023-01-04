<?php

namespace App\Services;

use App\Interfaces\ReportDebtSupplierInterface;



class ReportDebtSupplierService extends BaseService
{
    protected $report;

    function __construct(ReportDebtSupplierInterface $report)
    {
        $this->report = $report;

    }

    public function getListPaginate($perPage = 20, $filter)
    {

        return $this->report->getListPaginate($perPage, $filter);

    }


}
