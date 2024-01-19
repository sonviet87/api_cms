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
        $type = $request->input("type", 'months');
        $startDay = $request->input("startDay", '');
        $endDay = $request->input("endDay", '');
        $groupMember = $request->input("groupMember", '');
        $filter = [
            'startDay'  => $startDay,
            'endDay'  => $endDay,
            'groupMember'  => $groupMember,
            'status' => 6
        ];

        $rs = $this->kpiService->getList($filter);

        $arrParams = [
            'target_profit_months'=>$rs->get('target_profit_months'),
            'target_profit_3_months' => $rs->get('target_profit_3_months'),
            'target_profit_12_months' => $rs->get('target_profit_12_months'),
            'target_customer_months' => $rs->get('target_customer_months'),
            'target_customer_3_months' => $rs->get('target_customer_3_months'),
            'target_customer_12_months' => $rs->get('target_customer_12_months'),
            'target_debts_months' => $rs->get('target_debts_months'),
            'target_debts_3_months' => $rs->get('target_debts_3_months'),
            'target_debts_12_months' => $rs->get('target_debts_12_months'),
            'total_account_new' => $rs->get('total_account_new'),
            'total_profit' => $rs->get('total_profit'),
            'account_new' => $rs->get('account_new'),
            'customer_conditions_months' => $rs->get('customer_conditions_months'),
            'goal_percent_customer' => $rs->get('goal_percent_customer'),
            'total_percent_profit_months' => $rs->get('total_percent_profit_months'),
            'total_percent_profit_max_70_months' => $rs->get('total_percent_profit_max_70_months'),
        ];
        $arrForget = [
            'target_profit_months',
            'target_profit_3_months',
            'target_profit_12_months' ,
            'target_customer_months' ,
            'target_customer_3_months',
            'target_customer_12_months',
            'target_debts_months',
            'target_debts_3_months',
            'target_debts_12_months',
            'total_account_new',
            'total_profit',

            'customer_conditions_months',
            'goal_percent_customer',
            'total_percent_profit_months',
            'total_percent_profit_max_70_months',
            'account_new'
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
