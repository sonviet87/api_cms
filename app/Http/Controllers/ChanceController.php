<?php

namespace App\Http\Controllers;


use App\Http\Resources\ChanceCollection;
use App\Http\Resources\ChanceResource;
use App\Http\Resources\ContactCollection;
use App\Services\ChanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChanceController extends RestfulController
{
    protected $chanceService;

    public function __construct(ChanceService $chanceService)
    {
        parent::__construct();
        $this->chanceService = $chanceService;

        $this->middleware(['permission:account-delete'])->only('destroy');
        $this->middleware(['permission:account-create'])->only('store');
        $this->middleware(['permission:account-edit'])->only('update');
        $this->middleware(['permission:account-list'])->only('index');
    }

    /**
     * Get all approved products with paginate
     * @return mixed
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input("per_page", 20);

            $user_id = $request->input("user_id", '');
            $account_id = $request->input("account_id", '');
            $startDay = $request->input("startDay", '');
            $endDay = $request->input("endDay", '');
            $contact_id = $request->input("contact_id", '');
            $list = $request->input("list", '');
            $filter = [
                'user_id'  => $user_id,
                'account_id'  => $account_id,
                'contact_id'  => $contact_id,
                'startDay'  => $startDay,
                'endDay'  => $endDay,
                'list'  => $list,
            ];
            $rs = $this->chanceService->getListPaginate($perPage,$filter);

            return new ChanceCollection($rs);
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

            $accounts = $this->chanceService->getList();
            return new ChanceCollection($accounts);
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }
    /**
     * Get contact by id
     * @return mixed
     */
    public function getListContactByID(Request $request,$id)
    {
        try {
            $contacts = $this->chanceService->getListContactByID($id);

            if(isset($contacts['status'])){
                return $this->_error($contacts['message']);
            }
            return new ChanceCollection($contacts);
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
            $result = $this->chanceService->createNew($data);
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
            $result = $this->chanceService->getAccountByID($id);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return  new ChanceResource(($result['data']));
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
            $result = $this->chanceService->update($id, $data);
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
            $result = $this->chanceService->destroyAccountByIDs($ids);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    public function updateStatus(Request $request){
        $this->validate($request, [
            'id' => 'required',
            'status' => 'required',
        ]);
        try{
            $id = $request->input('id');
            $status = $request->input('status');

            $result = $this->chanceService->updateStatus($id, $status);

            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_response($result['data'],$result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    public function updateProgress(Request $request){
        $this->validate($request, [
            'id' => 'required',
            'completed' => 'required',
        ]);
        try{
            $id = $request->input('id');
            $progress = $request->input('completed');

            $result = $this->chanceService->updateProgress($id, $progress);

            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_response($result['data'],$result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

}
