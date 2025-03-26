<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;

class DashboardController extends RestfulController
{
    protected $dashboardService;
    public function __construct(DashboardService $dashboardService)
    {
        parent::__construct();
        $this->dashboardService = $dashboardService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $startDay = $request->input("startDay", '');
        $endDay = $request->input("endDay", '');

        $filter = [
            'startDay'  => $startDay,
            'endDay'  => $endDay,
        ];

        $rs = $this->dashboardService->getList($filter);

        return $this->_response( $rs );

    }


}
