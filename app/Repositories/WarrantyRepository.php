<?php
namespace App\Repositories;

use App\Interfaces\WarrantyInterface;
use App\Models\Warranty;


class WarrantyRepository implements WarrantyInterface {
    protected $model;
    function __construct(Warranty $warranty){
        $this->model = $warranty;
    }

    public function getList(){
        return $this->model->all();
    }

    public function getListPaginate($perPage = 20,$filter){
        $query = $this->model;

        if(!empty($filter)) {
            if (isset($filter['fp_id']) && $filter['fp_id'] != '') {
                $query = $query->where('fp_id', $filter['fp_id']) ;
            }
            if (isset($filter['keyword']) && $filter['keyword'] != '') {
                $query = $query->where('name', $filter['keyword']);
            }
            if (isset($filter['serial']) && $filter['serial'] != '') {
                $query = $query->whereJsonContains('details', ['serial' => $filter['serial']]);
                //$query = $query->whereJsonContains('details', [['serial' => $filter['serial']]]);

              /* $query = $query->whereRaw(
                    'json_contains(details, ?)',
                    [
                        json_encode(['serial' => $filter['serial']])
                    ]
                );*/
               // $query = $query->whereJsonContains('details->serial',$filter['serial']);
                // $query = $query->where('details->serial', 'like', '%'.$filter['serial'].'%');
               // dd($query->toSql());

            }
        }
        return $query->orderBy('created_at', 'desc')->paginate($perPage);
        //return $this->model->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function create($data){
        return $this->model->create($data);
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
