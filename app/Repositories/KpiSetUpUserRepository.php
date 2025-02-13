<?php
namespace App\Repositories;

use App\Interfaces\KpiSetUpUserInterface;
use App\Models\KpiSetUpUser;
use Illuminate\Support\Facades\Auth;


class KpiSetUpUserRepository implements KpiSetUpUserInterface {
    protected $model;
    function __construct(KpiSetUpUser $setUpUserp){
        $this->model = $setUpUserp;
    }

    public function getList($filter){
        $query = $this->model;
        if(!empty($filter)) {
           /* if (isset($filter['user_id']) && $filter['user_id'] != '') {
                $query = $query->where('user_id', $filter['user_id']) ;
            }*/
            if (isset($filter['year']) && $filter['year'] != '') {
                $query = $query->where('year', $filter['year']) ;
            }

        }
        if (!Auth::user()->hasPermissionTo('kpi-sale-all-user')) {
            $query->where('user_id', Auth::id());

            $tempQuery  = $query->first();
            if($tempQuery){
                $staffManagers = $tempQuery->staffManagers->pluck('user_id');
                $uniqueStaffManagers = $staffManagers->unique()->toArray();
                $uniqueStaffManagers[] = Auth::id();

                $query = $query->whereIn('user_id',$uniqueStaffManagers);

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
        return $this->model->with(['staffManagers.staffConditions','debtItems','saleItems'])->find($id);
    }

    public function getByUserID($id){
        return $this->model->with(['staffManagers.staffConditions','debtItems','saleItems'])->where('user_id',$id);
    }

    public function update($id, $data){
        return $this->model->where('id', $id)->update($data);
    }

    public function destroy($ids){
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function getByUser($user_id,$year){
        return $this->model->where('user_id',$user_id)->where('year',$year)->first();
    }
    public function checkIdKpiCurrentYear($arrID){
        $currentYear = date('Y');
        return $this->model->whereIn('id', $arrID)
            ->where('year', $currentYear)
            ->pluck('id')->toArray();;
    }

}
