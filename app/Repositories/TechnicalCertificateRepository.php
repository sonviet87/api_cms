<?php
namespace App\Repositories;

use App\Interfaces\TechnicalCertificateInterface;
use App\Models\TechnicalCetificate;


class TechnicalCertificateRepository implements TechnicalCertificateInterface {
    protected $model;
    function __construct(TechnicalCetificate $settings){
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

    public function getListbyUsers($filter = []){
        $query = $this->model;

        if (isset($filter['selectedYear']) && $filter['selectedYear'] != '') {
            $query = $query->whereYear('start_date','=' ,$filter['selectedYear']);
        }

        if (isset($filter['users'])) {

            $query = $query->where('user_id', $filter['users']) ;
        }

        $list = $query->orderBy('id', 'desc')->get();

        $summary = $query->selectRaw('COUNT(*) as total')
            ->selectRaw('SUM(goals = 1) as goals_1')
            ->selectRaw('SUM(goals = 2) as goals_2')
            ->first();

        return [
            'list' => $list,
            'summary' => [
                'total' => $summary->total ?? 0,
                'goals_1' => $summary->goals_1 ?? 0,
                'goals_2' => $summary->goals_2 ?? 0,
            ],
        ];
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


}
