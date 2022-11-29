<?php

namespace App\Http\Controllers;


use App\Http\Resources\FPCollection;
use App\Http\Resources\FPResource;
use App\Http\Resources\SupplierCollection;
use App\Services\FPService;
use App\Services\SupplierService;
use Illuminate\Http\Request;

class FPController extends RestfulController
{
    protected $fpService;

    public function __construct(FPService $fpService)
    {
        parent::__construct();
        $this->fpService = $fpService;
        //$this->middleware(['permission:account-list|account-create|account-edit|account-delete']);
    }

    /**
     * Get all approved products with paginate
     * @return mixed
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input("per_page", 20);
            $suppliers = $this->fpService->getListPaginate($perPage);

            return new FPCollection($suppliers);
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
            'account_id' => 'bail|required',
            'contact_id' => 'bail|required',
        ]);
        try{
            $data = $request->all();
            $result = $this->fpService->createNew($data);
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
            $result = $this->fpService->getByID($id);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
           // return $this->_response($result['data']);
            return  new FPResource($result['data']);
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
            'account_id' => 'bail|required',
            'contact_id' => 'bail|required',
        ]);
        try{
            $data = $request->all();
            $result = $this->fpService->update($id, $data);
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
            $result = $this->fpService->destroyByIDs($ids);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

}
