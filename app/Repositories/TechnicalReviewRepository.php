<?php
namespace App\Repositories;

use App\Interfaces\TechnicalReviewInterface;
use App\Models\TechnicalReview;

class TechnicalReviewRepository implements TechnicalReviewInterface {
    protected $model;
    function __construct(TechnicalReview $settings){
        $this->model = $settings;
    }

    public function getList($perpage = 20,$filter = []){
        $query = $this->model;
        if(!empty($filter)) {
            if (isset($filter['user_id']) && $filter['user_id'] != '' ) {

                $query = $query->where('user_id',$filter['user_id']);
            }
            if (isset($filter['search']) && $filter['search'] != '') {
                $query = $query->where('name', 'like', "%{$filter['search']}%");
            }
        }
        return $query->with('user')->orderBy('id', 'asc')->paginate($perpage);
    }

    public function getAll(){
        $query = $this->model;
        return $query->orderBy('id', 'desc')->get();
    }

    public function create($data){
        return $this->model->insert($data);
    }

    public function getByID($id){
        return $this->model->find($id);
    }

    public function update($id, $data){
        return $this->model->where('id', $id)->update($data);
    }


    public function destroy($ids){
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function getLowestPointByUsers($filter = [])
    {
        $query = $this->model;

        if (!empty($filter['selectedYear'])) {
            $query = $query->whereYear('start_date', '=', $filter['selectedYear']);
        }

        if (!empty($filter['users'])) {
            $query = $query->where('user_id', $filter['users']);
        }

        $lowestPoint = $query->min('points');

        return $lowestPoint ?? 0;
    }

}
