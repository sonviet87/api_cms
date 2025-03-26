<?php

namespace App\Http\Controllers;


use App\Http\Resources\FPCollection;
use App\Services\ReportService;
use Illuminate\Http\Request;


class ReportController extends RestfulController
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        parent::__construct();
        $this->reportService = $reportService;

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
            $type_fp = $request->input("type_fp", '');
            $category_id = $request->input("category_id", '');
            $startDay = $request->input("startDay", '');
            $endDay = $request->input("endDay", '');
            $supplier_id = $request->input("supplier_id", '');
            $list = $request->input("list", '');
            $filter = [
                'user_id'  => $user_id,
                'account_id'  => $account_id,
                'startDay'  => $startDay,
                'endDay'  => $endDay,
                'type_fp'  => $type_fp,
                'category_id'  => $category_id,
                'supplier_id'  => $supplier_id,
                'list'  => $list,
            ];

            $reports = $this->reportService->getListPaginate($perPage, $filter);

            return new FPCollection($reports);
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    public function list(Request $request)
    {
        try {
            $perPage = $request->input("per_page", 20);
            $user_id = $request->input("user_id", '');
            $account_id = $request->input("account_id", '');
            $type_fp = $request->input("type_fp", '');
            $category_id = $request->input("category_id", '');
            $startDay = $request->input("startDay", '');
            $endDay = $request->input("endDay", '');
            $supplier_id = $request->input("supplier_id", '');
            $filter = [
                'user_id'  => $user_id,
                'account_id'  => $account_id,
                'startDay'  => $startDay,
                'endDay'  => $endDay,
                'type_fp'  => $type_fp,
                'category_id'  => $category_id,
                'supplier_id'  => $supplier_id,
            ];

            $reports = $this->reportService->getListPaginate($perPage, $filter);

            return new FPCollection($reports);
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }


}
