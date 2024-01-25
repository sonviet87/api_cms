<?php
namespace App\Repositories;

use App\Constants\FPConst;
use App\Interfaces\DebtInterface;
use App\Interfaces\FPInterface;
use App\Models\Debt;
use App\Models\FP;
use App\Models\Supplier;


class DebtRepository implements DebtInterface {
    protected $model;
    function __construct(Debt $debt){
        $this->model = $debt;
    }

    public function getList(){
        return $this->model->all();
    }

    public function getListPaginate($perPage = 20,$filter = []){
        $query = $this->model;
        if(!empty($filter)) {
            if (isset($filter['search']) && $filter['search'] != '') {
                $search = $filter['search'];
                $query = $query->where('name', 'like', "%{$filter['search']}%")
                ->orWhereHas('fp', function ($query) use ( $search){
                    $query->where('code', 'like', '%'. $search.'%')
                    ->orWhereHas('account', function ($query) use ( $search){
                        $query->where('name', 'like', '%'. $search.'%');
                    }) ;;
                }) ;
            }

        }
        //dd($query->toSql() );
        return  $query->with(['fp'])->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getListKpi($filter = []) {
        $query = $this->model;

        if (isset($filter['startDay']) && $filter['startDay'] != '' && isset($filter['endDay']) && $filter['endDay'] != '') {
            $statDay = date('Y-m-d H:i:s', strtotime($filter['startDay']));
            $endDay = date('Y-m-d H:i:s', strtotime($filter['endDay']));
            $query = $query->whereDate('created_at', '>=' ,$statDay)->whereDate('created_at', '<=' ,$endDay);
        }

        if (isset($filter['users']) && $filter['users'] != '') {
            $users = $filter['users'];
            $query = $query->whereHas('fp', function ($query) use ($users) {
                $query->whereIn('user_assign', $users);
            });
        }

        $query = $query->with('fp')
            ->where('isDone', 1)
            ->orderBy('created_at', 'desc')
            ->get();

        foreach ($query as $item) {
            $dateInvoice = $item->fp->date_invoice;
            $dateCollection = $item->date_collection;

            // Chắc chắn rằng đây là đối tượng Carbon hoặc chuyển đổi nó nếu cần
            $dateInvoice = \Carbon\Carbon::parse($dateInvoice);
            $dateCollection = \Carbon\Carbon::parse($dateCollection);

            // Thực hiện so sánh $dateInvoice và $dateCollection
            $diff = $dateInvoice->startOfDay()->diffInDays($dateCollection);

            // Thực hiện xử lý với $diff
            $item->setAttribute('diff', $diff);
            $item->setAttribute('day_debuts_allows', $item->fp->account?->debt);
        }
        return $query;
    }



    public function create($data){
        $debt = $this->model->create($data);
        $debt->code = 'CNKH'.$debt->id;
        $debt->save();
        return $debt;
    }

    public function getByID($id){
        return $this->model->find($id);
    }

    public function update($id, $data){
        return $this->model->find($id)->update($data);
    }

    public function destroy($ids){
        return $this->model->whereIn('id', $ids)->delete();
    }



}
