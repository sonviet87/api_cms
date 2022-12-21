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
            $type_fp = $request->input("type_fp", '');
            $startDay = $request->input("startDay", '');
            $endDay = $request->input("endDay", '');
            $filter = [
                'user_id'  => $user_id,
                'startDay'  => $startDay,
                'endDay'  => $endDay,
                'type_fp'  => $type_fp,
            ];
            $reports = $this->reportService->getListPaginate($perPage, $filter);

            return new FPCollection($reports);
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }



}
