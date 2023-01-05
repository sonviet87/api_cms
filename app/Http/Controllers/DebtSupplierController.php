<?php

namespace App\Http\Controllers;

use App\Http\Resources\DebtCollection;

use App\Http\Resources\DebtResource;
use App\Http\Resources\DebtSupplierCollection;
use App\Http\Resources\DebtSupplierResource;
use App\Http\Resources\FPDetailsCollection;
use App\Http\Resources\SupplierDebtCollection;
use App\Services\DebtSupplierService;
use Illuminate\Http\Request;


class DebtSupplierController extends RestfulController
{
    protected $debtService;

    public function __construct(DebtSupplierService $debtService)
    {
        parent::__construct();
        $this->debtService = $debtService;

       /* $this->middleware(['permission:fp-delete'])->only('destroy');
        $this->middleware(['permission:fp-create'])->only('store');
        $this->middleware(['permission:fp-edit'])->only('update');
        $this->middleware(['permission:fp-list'])->only('index');
        $this->middleware(['permission:fp-approved-manager|fp-approved-sale'])->only('updateStatus');*/
    }

    /**
     * Get all approved products with paginate
     * @return mixed
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input("per_page", 20);
            $search = $request->input("keyword", '');
            $filter = [
                'search'  => $search,
            ];
            $debts = $this->debtService->getListPaginate($perPage, $filter);

            return new DebtSupplierCollection($debts);
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Get all approved products with paginate
     * @return mixed
     */
    public function list(Request $request)
    {
        try {
            $debts = $this->debtService->getList();
            return new DebtCollection($debts);
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Get all approved products with paginate
     * @return mixed
     */
    public function getSupplierbyIDFP(Request $request)
    {
        $this->validate($request, [
            'fp_id' => 'bail|required',
            'supplier_id' => 'bail|required',
        ]);
        try {
            $supplier_id = $request->input("supplier_id");
            $fp_id = $request->input("fp_id");

            $debts = $this->debtService->getSupplierbyIDFP($supplier_id, $fp_id);

            //return $this->_response($debts);
            return new FPDetailsCollection($debts);
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Get all approved products with paginate
     * @return mixed
     */
    public function getListSupplierbyIDFP(Request $request)
    {
        $this->validate($request, [
            'fp_id' => 'bail|required',
        ]);
        try {
            $fp_id = $request->input("fp_id");
            $debts = $this->debtService->getListSupplierbyIDFP( $fp_id);

           // return $this->_response($debts);
            return new SupplierDebtCollection($debts);
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Create a Account
     * @return mixed
     */
    public function store(Request $request){
        $this->validate($request, [
            'name' => 'bail|required',
            'fp_id' => 'bail|required',
            'date_over' => 'bail|required',

        ]);
        try{
            $data = $request->all();
            $result = $this->debtService->createNew($data);
            if($result['status']==false){
                return $this->_error($result['message']);
            }


            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Get a account by id
     * @param interger $id
     * @return mixed
     */
    public function show($id){
        try{
            $result = $this->debtService->getByID($id);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
           // return $this->_response($result['data']);
            return  new DebtSupplierResource($result['data']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Update a account by  id
     * @return mixed
     */
    public function update(Request $request, $id){

        $this->validate($request, [
            'name' => 'bail|required',
            'fp_id' => 'bail|required',
            'date_over' => 'bail|required',
        ]);
        try{
            $data = $request->all();
            $result = $this->debtService->update($id, $data);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Delete a list of account by an array of  id
     * @param array $ids
     * @return mixed
     */
    public function destroy(Request $request){
        $this->validate($request, [
            'ids' => 'required|array|min:1',
        ]);
        try{
            $ids = $request->input('ids');
            $result = $this->debtService->destroyByIDs($ids);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }



}
