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
        $pagingArr = $rs->toArray();
        return $this->_response([

            'kpi' => $pagingArr
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
