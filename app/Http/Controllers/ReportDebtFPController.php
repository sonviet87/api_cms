<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReportDebtFPCollection;
use App\Services\ReportDebtFPService;
use Illuminate\Http\Request;


class ReportDebtFPController extends RestfulController
{
    protected $reportDebtFPService;

    public function __construct(ReportDebtFPService $reportDebtFPService)
    {
        parent::__construct();
        $this->reportDebtFPService = $reportDebtFPService;

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

            $startDay = $request->input("startDay", '');
            $endDay = $request->input("endDay", '');
            $list = $request->input("list", '');
            $filter = [
                'user_id'  => $user_id,
                'account_id'  => $account_id,
                'startDay'  => $startDay,
                'endDay'  => $endDay,
                'fp_id'  => $fp_id,
                'isDone'  => $isDone,
                'list'  => $list,
            ];

            $reports = $this->reportDebtFPService->getListPaginate($perPage, $filter);
            //return $this->_response($reports);
            return new ReportDebtFPCollection($reports);
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }



}
