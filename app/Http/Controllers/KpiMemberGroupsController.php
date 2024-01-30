<?php

namespace App\Http\Controllers;


use App\Http\Resources\KpiMemberGroupsCollection;
use App\Http\Resources\KpiMemberGroupsResource;
use App\Services\KpiMemberGroupsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KpiMemberGroupsController extends RestfulController{

    protected  $kpiMemberGroups;

    public function __construct(KpiMemberGroupsService $kpiMemberGroups)
    {
        parent::__construct();
        $this->kpiMemberGroups = $kpiMemberGroups;


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
            $rs = $this->kpiMemberGroups->getListPaginate($perPage, $filter);

            return new KpiMemberGroupsCollection($rs);
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
        try {
            $search = $request->input("search", '');
            $filter = [
                'search'  => $search,
            ];
            $rs = $this->kpiMemberGroups->getList($filter);
            return new KpiMemberGroupsCollection($rs);
        } catch (\Exception $e) {
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }

    /**
     * Create a Kpi members groups
     * @return mixed
     */
    public function store(Request $request){

        try{
            $data = $request->all();
            $result = $this->kpiMemberGroups->create($data);
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
            $result = $this->kpiMemberGroups->getByID($id);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            // return $this->_response($result['data']);
            return  new KpiMemberGroupsResource($result['data']);
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
            $result = $this->kpiMemberGroups->update($id, $data);
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
            $result = $this->kpiMemberGroups->destroyByIDs($ids);
            if($result['status']==false){
                return $this->_error($result['message']);
            }
            return $this->_success($result['message']);
        }catch(\Exception $e){
            return $this->_error($e, self::HTTP_INTERNAL_ERROR);
        }
    }
}