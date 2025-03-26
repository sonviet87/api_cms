<?php

namespace App\Http\Controllers;

use App\Http\Resources\AccountCollection;
use App\Http\Resources\ContactCollection;
use App\Http\Resources\CostsTeamCollection;
use App\Http\Resources\CostsTeamResource;
use App\Interfaces\CostsTeamInterface;
use App\Services\AccountService;
use App\Services\CostsFixedService;
use App\Services\CostsService;
use App\Services\CostsTeamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CostsTeamController extends RestfulController
{
    protected $costsService;

    public function __construct(CostsTeamService $costsService)
    {
        parent::__construct();
        $this->costsService = $costsService;


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
            $rs = $this->costsService->getListPaginate($perPage,$filter);


            return new CostsTeamCollection($rs);
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
            $accounts = $this->costsService->getList();
            return new CostsTeamCollection($accounts);
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
        ]);
        try{
            $data = $request->all();
            $result = $this->costsService->createNew($data);
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
            $result = $this->costsService->gettByID($id);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return new CostsTeamResource($result['data']);
           // return $this->_response($result['data']);
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
            'name' => 'bail|required'
        ]);
        try{
            $data = $request->all();
            $result = $this->costsService->update($id, $data);
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
       // dd(Auth::user()->first()->hasPermission(['account-list']));
        try{
            $ids = $request->input('ids');
            $result = $this->costsService->destroyByIDs($ids);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

}
