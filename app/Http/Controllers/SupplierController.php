<?php

namespace App\Http\Controllers;


use App\Http\Resources\SupplierCollection;
use App\Http\Resources\SupplierListCollection;
use App\Services\SupplierService;
use Illuminate\Http\Request;

class SupplierController extends RestfulController
{
    protected $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        parent::__construct();
        $this->supplierService = $supplierService;
        //$this->middleware(['permission:account-list|account-create|account-edit|account-delete']);
        $this->middleware(['permission:supplier-delete'])->only('destroy');
        $this->middleware(['permission:supplier-create'])->only('store');
        $this->middleware(['permission:supplier-edit'])->only('update');
        $this->middleware(['permission:supplier-list'])->only('index');
    }

    /**
     * Get all approved products with paginate
     * @return mixed
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input("per_page", 20);
            $search = $request->input("search", '');
            $filter = [
                'search'  => $search,
            ];
            $suppliers = $this->supplierService->getListPaginate($perPage,$filter);

            return new SupplierCollection($suppliers);
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Get all approved
     * @return mixed
     */
    public function list(Request $request)
    {
        try {
            $suppliers = $this->supplierService->getList();

            return new SupplierListCollection($suppliers);
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
            'company' => 'bail|required',
        ]);
        try{
            $data = $request->all();
            $result = $this->supplierService->createNew($data);
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
            $result = $this->supplierService->getByID($id);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_response($result['data']);
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
            'company' => 'bail|required'
        ]);
        try{
            $data = $request->all();
            $result = $this->supplierService->update($id, $data);
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
            $result = $this->supplierService->destroyByIDs($ids);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

}
