<?php
namespace App\Repositories;

use App\Constants\FPConst;
use App\Constants\PermissionConst;
use App\Interfaces\FPInterface;
use App\Models\ContractCode;
use App\Models\FP;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class FPRepository implements FPInterface {
    protected $model;
    function __construct(FP $fp){
        $this->model = $fp;
    }

    public function getList($filter = []){
        $query = $this->model;
        if(!empty($filter)) {
            if (isset($filter['user_id']) && $filter['user_id'] != '') {
                $query = $query->where('user_id', $filter['user_id'])->orWhere('user_assign', $filter['user_id']) ;
            }
        }
        return $query->orderBy('id', 'desc')->get();
    }

    public function getListbyUsers($filter = []){
        $query = $this->model;

        if (isset($filter['startDay']) && $filter['startDay'] != '') {
            $statDayValue = date('Y-m-d',strtotime($filter['startDay']));
            $query = $query->whereDate('date_completed','>=' ,$statDayValue);
        }
        if (isset($filter['endDay']) && $filter['endDay'] != '') {

            $endDayValue = date('Y-m-d', strtotime($filter['endDay']));
            $query = $query->whereDate('date_completed','<=' ,$endDayValue);
        }
        if (isset($filter['users']) && count($filter['users'])) {

            $query = $query->whereIn('user_assign', $filter['users']) ;
        }

        if (isset($filter['status']) && $filter['status'] !="") {
            $query = $query->where('status',6);
        }

        return $query->orderBy('id', 'desc')->get();
    }

    public function getIDsUsersNotExistInCurrentUsers($filter = []){
        $query = $this->model;
        if (isset($filter['startDay']) && $filter['startDay'] != '') {
            $startDayValue = date('Y-m-d', strtotime($filter['startDay']));
            $query = $query->whereDate('date_completed','<' ,$startDayValue);
        }

        if (isset($filter['users']) && count($filter['users'])) {
            $query = $query->whereIn('user_assign', $filter['users']) ;
        }
        //dd($query->toSql());
        $query = $query->where('status',6)->get()->pluck('account_id');

        return $query;

    }


    public function getListPaginate($perPage = 20, $filter = [])
    {
        $query = $this->model;
        $user = Auth::user();

        if (!empty($filter)) {
            if (!empty($filter['search'])) {
                $search = $filter['search'];
                $query = $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('code', 'LIKE', "%{$search}%");
                });
            }

            if (!empty($filter['user_id'])) {
                $user_id = $filter['user_id'];
                $query = $query->where(function ($q) use ($user_id, $user) {
                    $q->where('user_id', $user_id)
                        ->orWhere('user_assign', $user_id)
                        ->orWhereHas('technicals', function ($q) use ($user) {
                            $q->where('user_id', $user->id);
                        });
                });
            }

            if (!empty($filter['staffs'])) {
                $query = $query->orWhereIn('user_assign', $filter['staffs']);
            }
        }

        // **Chỉ giới hạn status nếu user có quyền IS_SALE**
        if ($user->hasPermissionTo(PermissionConst::IS_SALE)) {
            $query = $query->whereNotIn('status', [FPConst::STATUS_NEW, FPConst::STATUS_PAKD]);
        }

        return $query->with(['user', 'account', 'contact'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }


    public function create($data){
        $fp = $this->model->create($data);
        if($fp->net_profit_percent >= 10) {
            $fp->status = 1;
            $fp->save();
        }
        return $fp;
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

    public function updateStatus($id, $status){
        $fp = $this->model->find($id);
        if($status == FPConst::STATUS_COMPLETED){
            $fp->selling = $fp->total_sell;
            $fp->margin = $fp->net_profit;
            $fp->date_completed =  Carbon::now()->startOfDay();
            $fp->save();
        }
        if($status == FPConst::STATUS_CONTRACT){
            if($fp->isCodeContract == 0){
                //increment code contract
                $contractCode = ContractCode::get()->first();
                $contractCode->code = $contractCode->code + 1;
                $contractCode->save();

                $currentYear = Carbon::now()->year;
                //add zero if code less than 10
                $codeContract = $contractCode->code;
                if($contractCode->code<10) $codeContract = '0'.$contractCode->code;
                //update code contract
                $fp->isCodeContract = 1;

                $fp->code_contract = 'HD-MV-TECH-'. $codeContract.'-'.$currentYear ;
            }

        }

        return $fp->update(['status'=> $status]);
    }

    public function getKpiFP($filter = [])
    {
        $query = $this->model;
        $user = Auth::user();

        if ($user->hasPermissionTo(PermissionConst::IS_SALE)) {
            $filter['users'] = [$user->id];

        }


        if (isset($filter['startDay']) && $filter['startDay'] != '') {
            $startDay = date('Y-m-d', strtotime($filter['startDay']));

            $query = $query->whereDate('created_at', '>=', $startDay);
        }


        if (isset($filter['endDay']) && $filter['endDay'] != '') {
            $endDay = date('Y-m-d', strtotime($filter['endDay']));
            $query = $query->whereDate('created_at', '<=', $endDay);
        }


        if (isset($filter['users']) && count($filter['users'])) {
            $query = $query->whereIn('user_assign', $filter['users']);
        }


        $data = $query->selectRaw('COUNT(*) as total, status')
            ->groupBy('status')
            ->get();


        $totalFp = $data->sum('total');
        $statusNew = $data->firstWhere('status', FPConst::STATUS_NEW)->total ?? 0;
        $statusPakd = $data->firstWhere('status', FPConst::STATUS_PAKD)->total ?? 0;
        $statusContract = $data->firstWhere('status', FPConst::STATUS_CONTRACT)->total ?? 0;


        return [
            'total_fp' => $totalFp,
            'status_new' => $statusNew,
            'status_pakd' => $statusPakd,
            'status_contract' => $statusContract,
        ];
    }


}
