<?php

namespace App\Http\Controllers;

use App\Services\KpiSaleService;
use App\Services\KpiTechnicalService;
use Illuminate\Http\Request;

class KpiTechnicalController extends RestfulController
{
    protected $kpiTechnicalService;
    public function __construct(KpiTechnicalService $kpiTechnicalService)
    {
        parent::__construct();
        $this->kpiTechnicalService = $kpiTechnicalService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->input("kpiType", '12');

        $selectedYear = $request->input("selectedYear", '');
        $groupMember = $request->input("groupMember", '');
        $filter = [

            'selectedYear'  => $selectedYear,
            'groupMember'  => $groupMember,
            'type' => $type
        ];

        $rs1 = $this->kpiTechnicalService->getList($filter);


        return $this->_response( $rs1 );
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
