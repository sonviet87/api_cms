<?php
namespace App\Repositories;
use App\Interfaces\KpiMemberGroupsInterface;
use App\Models\KpiMemberGroup;


class KpiMemberGroupsRepository implements KpiMemberGroupsInterface {
    protected $model;
    function __construct(KpiMemberGroup $memberGroup){
        $this->model = $memberGroup;
    }

    public function getList($filter){
        $query = $this->model;
        if(!empty($filter)) {
            if (isset($filter['user_id']) && $filter['user_id'] != '') {
                $query = $query->where('user_id', $filter['user_id']) ;
            }
        }
        return $query->orderBy('id', 'desc')->get();
    }

    public function getListPaginate($perPage = 20,$filter){
        $query = $this->model;
        if (isset($filter['user_id']) && $filter['user_id'] != '') {
            $user_id = $filter['user_id'];
            $query = $query->where('user_id', $user_id);
        }
        if (isset($filter['search']) && $filter['search'] != '') {
            $search = $filter['search'];
             $query = $query->where('name', 'LIKE', "%{$search}%") ;

        }
        return $query ->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create($data){
        return $this->model->create($data);
    }

    public function getByID($id){
        return $this->model->with(['customers','debts','users'])->find($id);
    }

    public function update($id, $data){
        return $this->model->where('id', $id)->update($data);
    }

    public function destroy($ids){
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function getListContactByID($id ){
        $account = $this->model->find($id);
        if ( empty ($account) ) {
            return ;
        }
        return $account->contacts;
    }
}
