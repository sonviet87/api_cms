<?php

namespace App\Http\Controllers;

use App\Services\KpiSaleService;
use App\Services\KpiSupplierService;
use App\Services\KpiTechnicalService;
use Illuminate\Http\Request;

class KpiSupplierController extends RestfulController
{
    protected $kpiSupplierService;
    public function __construct(KpiSupplierService $kpiSupplierService)
    {
        parent::__construct();
        $this->kpiSupplierService = $kpiSupplierService;
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

        $rs1 = $this->kpiSupplierService->getList($filter);


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
