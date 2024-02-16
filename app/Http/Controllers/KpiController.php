<?php

namespace App\Http\Controllers;

use App\Services\KpiService;
use Illuminate\Http\Request;

class KpiController extends RestfulController
{
    protected $kpiService;
    public function __construct(KpiService $kpiService)
    {
        parent::__construct();
        $this->kpiService = $kpiService;
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

        $rs = $this->kpiService->getList($filter);

        $arrParams = [
            'target_profit'=>$rs->get('target_profit'),
            'target_profit_months'=>$rs->get('target_profit_months'),
            'target_profit_3_months' => $rs->get('target_profit_3_months'),
            'target_profit_12_months' => $rs->get('target_profit_12_months'),
            'target_customer_months' => $rs->get('target_customer_months'),
            'target_customer_3_months' => $rs->get('target_customer_3_months'),
            'target_customer_12_months' => $rs->get('target_customer_12_months'),
            'target_customer' => $rs->get('target_customer'),
            'result_kpi_goals' => $rs->get('result_kpi_goals'),
            'conditions_debts_type_list' => $rs->get('conditions_debts_type_list'),
            'debuts' => $rs->get('debuts'),
            'debuts_percent' => $rs->get('debuts_percent'),
            'percentTotalSettings' => $rs->get('percentTotalSettings'),
            'record_setting_percent' => $rs->get('record_setting_percent'),
            'revenues' => $rs->get('revenues'),

            'total_account_new' => $rs->get('total_account_new'),
            'total_profit' => $rs->get('total_profit'),
            'account_new' => $rs->get('account_new'),
            'customer_conditions_type_list' => $rs->get('conditions_debts_type_list'),
            'goal_percent_customer' => $rs->get('goal_percent_customer'),
            'total_percent_profit' => $rs->get('total_percent_profit'),
            'total_percent_profit_max_70' => $rs->get('total_percent_profit_max_70'),
            'totalGoals' => $rs->get('totalGoals'),
            'totalPercentDebuts' => $rs->get('totalPercentDebuts'),
            'users' => $rs->get('users'),
            'totalSalary' => $rs->get('totalSalary'),
            'totalProfitMargin' => $rs->get('totalProfitMargin'),
            'totalPercentRevenues' => $rs->get('totalPercentRevenues'),
            'profit_percent_target' => $rs->get('profit_percent_target')
        ];
        $arrForget = [
            'totalPercentRevenues',
            'totalProfitMargin',
            'totalSalary',
            'target_profit_months',
            'target_profit_3_months',
            'target_profit_12_months',
            'target_profit',
            'users',
            'revenues',
            'target_customer_months' ,
            'target_customer_3_months',
            'target_customer_12_months',
            'target_customer',
            'result_kpi_goals',
            'record_setting_percent',
            'conditions_debts_type_list',
            'total_account_new',
            'total_profit',
            'customer_conditions',
            'goal_percent_customer',
            'total_percent_profit',
            'total_percent_profit_max_70',
            'account_new',
            'totalGoals',
            'debuts',
            'debuts_percent',
            'percentTotalSettings',
            'totalPercentDebuts',
            'profit_percent_target'
        ];
        $rs->forget($arrForget);
        $pagingArr = $rs->toArray();
        return $this->_response([
            'target_kpi' =>$arrParams,
            'data' => $pagingArr
        ]);
        //return new FPCollection($rs);

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
