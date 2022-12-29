<?php
namespace App\Repositories;


use App\Interfaces\FPDetailInterface;
use App\Models\FP;
use App\Models\FPDetail;


class FPDetailRepository implements FPDetailInterface {
    protected $model;
    function __construct(FPDetail $fpDetail){
        $this->model = $fpDetail;
    }

    public function create($data){
        return $this->model->insert($data);
    }

    public function update($id, $data){

        return $this->model->find($id)->update($data);

    }

    public  function getListSupplierbyIDFP($fp_id){
        $query = $this->model->distinct()->select('supplier_id')->with('supplier')->where("fp_id",$fp_id)->get();
        //dd($query->toSql() );
        return $query ;


    }

    public  function getSupplierbyIDFP($supplier_id,$fp_id){
        return $this->model->where("fp_id",$fp_id)->where("supplier_id",$supplier_id)->get();
    }

    public function getIDS($id){

        return $this->model->where("fp_id",$id)->get()->pluck('id');

    }

    public function destroy($ids){
        return $this->model->whereIn('id', $ids)->delete();
    }
}
