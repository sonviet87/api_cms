<?php

namespace App\Http\Controllers;


use App\Http\Resources\KpiMemberGroupsCollection;
use App\Http\Resources\KpiMemberGroupsResource;
use App\Http\Resources\KpiSettingSaleResource;
use App\Http\Resources\KpiSetUpUserCollection;
use App\Services\KpiMemberGroupsService;
use App\Services\KpiSetUpUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KpiSetUpUserController extends RestfulController{

    protected  $kpiSetUpUser;

    public function __construct(KpiSetUpUserService $kpiSetUpUser)
    {
        parent::__construct();
        $this->kpiSetUpUser = $kpiSetUpUser;


    }
    /**
     * Get all  Kpi members groups with paginate
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
            $rs = $this->kpiSetUpUser->getListPaginate($perPage, $filter);

            return new KpiSetUpUserCollection($rs);
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }
    /**
     * Get all  Kpi members groups with paginate
     * @return mixed
     */
    public function list(Request $request)
    {
        $this->validate($request, [

            'year' => 'bail|required',
        ]);
        try {
            $search = $request->input("search", '');
            $year = $request->input("year", '');
            $filter = [
                'search'  => $search,
                'year'  => $year,
            ];
            $rs = $this->kpiSetUpUser->getList($filter);
            return new KpiSetUpUserCollection($rs);
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Create a Kpi members groups
     * @return mixed
     */
    public function store(Request $request){
        $this->validate($request, [
            'name' => 'bail|required',
            'user_assign' => 'bail|required',
        ]);

        try{
            $data = $request->all();
            $result = $this->kpiSetUpUser->create($data);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }
    /**
     * Get a kpi member group by id
     * @param interger $id
     * @return mixed
     */
    public function show($id){
        try{
            $result = $this->kpiSetUpUser->getByID($id);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return  new KpiSettingSaleResource($result['data']);
        }catch(\Exception $e){
            /*return response()->json([
                'status' => false,
                'message' => 'Dữ liệu không tồn tại',
            ], Response::HTTP_NOT_FOUND);*/
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }
    /**
     * Get a  kpi setting by usser
     * @param interger $id
     * @return mixed
     */
    public function getIDByUser(Request $request){
        $this->validate($request, [
            'user_id' => 'bail|required',
            'year' => 'bail|required',
        ]);
        $data = $request->all();
        $user_id = $data['user_id'];
        $year = $data['year'];
        try{
            $result = $this->kpiSetUpUser->getByUser($user_id,$year);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return$result ;
            return $this->_success($result['data']);

        }catch(\Exception $e){

            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }
    /**
     * Get a  kpi setting by usser
     * @param interger $id
     * @return mixed
     */
    public function checkIdKpiCurrentYear(Request $request){
        $this->validate($request, [
            'arrID' => 'bail|required',
        ]);
        $data = $request->all();
        $arrIDJson = $data['arrID'];
        if (!$arrIDJson) {
            return response()->json(['error' => 'arrID is required'], 400);
        }

        $arrID = json_decode($arrIDJson, true);

        if (!is_array($arrID)) {
            return response()->json(['error' => 'Invalid arrID format'], 400);
        }
        try{
            $result = $this->kpiSetUpUser->checkIdKpiCurrentYear($arrID);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return$result ;
            return $this->_success($result['data']);

        }catch(\Exception $e){

            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }


    /**
     * Update a kpi member group  by  id
     * @return mixed
     */
    public function update(Request $request, $id){

        $this->validate($request, [
            'name' => 'bail|required',
        ]);
        try{
            $data = $request->all();
            $result = $this->kpiSetUpUser->update($id, $data);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Delete a list of kpi member group by an array of  id
     * @param array $ids
     * @return mixed
     */
    public function destroy(Request $request){
        $this->validate($request, [
            'ids' => 'required|array|min:1',
        ]);
        try{
            $ids = $request->input('ids');
            $result = $this->kpiSetUpUser->destroyByIDs($ids);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }
}
