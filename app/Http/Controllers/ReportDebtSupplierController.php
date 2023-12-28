<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReportDebtSupplierCollection;
use App\Services\ReportDebtSupplierService;
use Illuminate\Http\Request;


class ReportDebtSupplierController extends RestfulController
{
    protected $reportDebtSupplierService;

    public function __construct(ReportDebtSupplierService $reportDebtSupplierService)
    {
        parent::__construct();
        $this->reportDebtSupplierService = $reportDebtSupplierService;

    }

    /**
     * Get all approved products with paginate
     * @return mixed
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input("per_page", 20);
            $user_id = $request->input("user_id", '');
            $account_id = $request->input("account_id", '');
            $fp_id = $request->input("fp_id", '');
            $isDone = $request->input("isDone", '');
            $supplier_id = $request->input("supplier_id", '');

            $startDay = $request->input("startDay", '');
            $endDay = $request->input("endDay", '');
            $list = $request->input("list", '');
            $filter = [
                'user_id'  => $user_id,
                'account_id'  => $account_id,
                'startDay'  => $startDay,
                'endDay'  => $endDay,
                'fp_id'  => $fp_id,
                'supplier_id'  => $supplier_id,
                'isDone'  => $isDone,
                'list'  => $list,
            ];

            $reports = $this->reportDebtSupplierService->getListPaginate($perPage, $filter);
            //return $this->_response($reports);

            return new ReportDebtSupplierCollection($reports);
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }



}
