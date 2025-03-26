<?php
namespace App\Repositories;
use App\Interfaces\CostsTeamInterface;
use App\Models\CostsTeam;
use GuzzleHttp\Psr7\Query;


class CostsTeamRepository implements CostsTeamInterface {
    protected $model;
    function __construct(CostsTeam $costsTeam){
        $this->model = $costsTeam;
    }

    public function getList($filter=[]){
        $query = $this->model;
        if(!empty($filter)) {
            if (isset($filter['user_id']) && $filter['user_id'] != '') {
                $query = $query->where('user_id', $filter['user_id']) ;
            }
        }
        return $query->orderBy('id', 'desc')->get();
    }

    public function getAll($data){
        $query = $this->model;

        $query = $query->whereIn('id', $data) ;

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
        return $this->model->find($id);
    }

    public function gettTeamByYear($year){
        $query = $this->model;
        return $query->where('year',$year)->get();
    }


    public function update($id, $data){
        return $this->model->where('id', $id)->update($data);
    }

    public function destroy($ids){
        return $this->model->whereIn('id', $ids)->delete();
    }


}
