<?php

namespace App\Http\Controllers;

use App\Services\KpiSaleService;
use Illuminate\Http\Request;

class KpiSaleController extends RestfulController
{
    protected $kpiSaleService;
    public function __construct(KpiSaleService $kpiSaleService)
    {
        parent::__construct();
        $this->kpiSaleService = $kpiSaleService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->input("kpiType", '1');
        $startDay = $request->input("startDay", '');
        $endDay = $request->input("endDay", '');
        $groupMember = $request->input("groupMember", '');
        $filter = [
            'startDay'  => $startDay,
            'endDay'  => $endDay,
            'groupMember'  => $groupMember,
            'status' => 6,
            'type' => $type
        ];

        $rs = $this->kpiSaleService->getList($filter);

        $arrParams = [
            'total_selling'=>$rs->get('total_selling'),
            'sale_achievements' => $rs->get('sale_achievements'),
            'current_sale_achievements' => $rs->get('current_sale_achievements'),
            'debts_kpi' => $rs->get('debts_kpi'),
            'total_achievements' => $rs->get('total_achievements'),
            'total_percentage' => $rs->get('total_percentage'),
            'sale_setting_total' => $rs->get('sale_setting_total'),
            'target_sale' => $rs->get('target_sale'),
            'target_current_sale' => $rs->get('target_current_sale'),
            'salary' => $rs->get('salary'),
            'staff_manager' => $rs->get('staff_manager'),
            'kpi_company' => $rs->get('kpi_company'),
            'target_company' => $rs->get('target_company'),
            'total_selling_year' => $rs->get('total_selling_year'),
            'total_points' => $rs->get('total_points'),
            'target_kpi_company' => $rs->get('target_kpi_company'),

            'total_all_bouns' => $rs->get('total_all_bouns'),
            'min_bonus_setting_progress' => $rs->get('min_bonus_setting_progress'),
            'percent_sale' => $rs->get('percent_sale'),
            'percent_current_sale' => $rs->get('percent_current_sale'),
            'percent_debts' => $rs->get('percent_debts'),

        ];

        return $this->_response([
            'target_kpi' =>$arrParams,

        ]);
    }

    public function getListKpisale(Request $request)
    {
        $type = $request->input("kpiType", '1');
        $startDay = $request->input("startDay", '');
        $endDay = $request->input("endDay", '');
        $groupMember = $request->input("listKpi", '');
        $filter = [
            'startDay'  => $startDay,
            'endDay'  => $endDay,
            'listKpi'  => $groupMember,
            'type' => $type
        ];

        $rs = $this->kpiSaleService->getListKpiSale($filter);
        return $this->_response([
            'sale_kpi'=>$rs

        ]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
