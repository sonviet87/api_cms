<?php

namespace App\Http\Controllers;

use App\Http\Resources\TechnicalCertificateCollection;
use App\Services\TechnicalCertificateService;
use Illuminate\Http\Request;


class TechnicalCertificateController extends RestfulController
{
    protected $technicalCetificate;

    public function __construct(TechnicalCertificateService $technicalCetificate)
    {
        parent::__construct();
        $this->technicalCetificate = $technicalCetificate;

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
            $user_id = $request->input("user_id", '');
            $filter = [
                'search'  => $search,
                'user_id'  => $user_id,
            ];
            $rs = $this->technicalCetificate->getList($perPage,$filter);

            return new TechnicalCertificateCollection($rs);
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
            $rs = $this->technicalCetificate->getList();
            return $this->_response([
                'data' =>$rs,

            ]);
           // return new AccountCollection($accounts);
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
            $result = $this->technicalCetificate->create($data);
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
            $result = $this->technicalCetificate->getByID($id);
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
            'name' => 'bail|required'
        ]);
        try{
            $data = $request->all();
            $result = $this->technicalCetificate->update($id, $data);
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
            $result = $this->technicalCetificate->destroyByIDs($ids);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

}
