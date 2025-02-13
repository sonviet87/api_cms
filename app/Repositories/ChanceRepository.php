<?php
namespace App\Repositories;
use App\Constants\ChanceConst;
use App\Interfaces\ChanceInterface;
use App\Models\Chance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class ChanceRepository implements ChanceInterface {
    protected $model;
    function __construct(Chance $chance){
        $this->model = $chance;
    }

    public function getList($filter){
        $query = $this->model;
        if(!empty($filter)) {
            if (isset($filter['user_id']) && $filter['user_id'] != '') {
                $query = $query->where('user_id', $filter['user_id']) ;
            }
            if (isset($filter['staffs']) && $filter['staffs'] != '') {

                $query = $query->orwhereIn('user_assign',$filter['staffs']);
            }
        }
        return $query->orderBy('id', 'desc')->get();
    }

    public function getListPaginate($perPage = 20,$filter){
        $query = $this->model;
        if (isset($filter['user_id']) && $filter['user_id'] != '') {
            $query = $query->where('user_assign', $filter['user_id']);
        }

        if (isset($filter['account_id']) && $filter['account_id'] != '') {
            $query = $query->where('account_id', $filter['account_id']);
        }

        if (isset($filter['contact_id']) && $filter['contact_id'] != '') {
            $query = $query->where('contact_id', $filter['contact_id']);
        }
        if (isset($filter['startDay']) && $filter['startDay'] != '' && isset($filter['endDay']) && $filter['endDay'] != '') {
            $statDay = date('Y-m-d',strtotime($filter['startDay']));
            $endDay = date('Y-m-d', strtotime($filter['endDay']));

             $query = $query->whereDate('start_day', '>=', $statDay)->whereDate('start_day', '<=', $endDay)
                 ->where(function ($query) {
                     $query->where('completed', ChanceConst::IN_PROGRESS)
                         ->orWhere(function ($query) {
                             $query->whereMonth('end_day', '=', DB::raw('MONTH(start_day)'))
                                 ->orWhere(function ($query) {
                                     $query->whereMonth('end_day', '>', DB::raw('MONTH(start_day)'));
                                 });
                         });
                 });

        }
        if (isset($filter['staffs']) && $filter['staffs'] != '') {

            $query = $query->orwhereIn('user_assign',$filter['staffs']);
        }
        if(isset($filter['list']) && $filter['list'] == 'list') return  $query->orderBy('created_at', 'desc')->get();
        return $query ->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function getChanceStatsByDateRange($filter)
    {
        if (!isset($filter['startDay'], $filter['endDay']) || $filter['startDay'] == '' || $filter['endDay'] == '') {
            return [
                'total' => 0,
                'in_progress' => 0,
                'progress_success' => 0,
                'progress_failed' => 0,
            ];
        }

        $query = $this->model;
        $startDate = date('Y-m-d', strtotime($filter['startDay']));
        $endDate = date('Y-m-d', strtotime($filter['endDay']));
        $user = Auth::user();
        if (!$user->hasPermissionTo('is_dashboard_all')) {
            $filter['users'] = [$user->id];

        }
        if (isset($filter['users']) && count($filter['users'])) {
            $query = $query->whereIn('user_assign', $filter['users']);
        }

        // Lấy tổng số dòng và nhóm theo trạng thái completed
        $data = $query
            ->selectRaw('count(*) as total, completed')
            ->whereDate('start_day', '>=', $startDate)
            ->whereDate('start_day', '<=', $endDate)
            ->groupBy('completed')
            ->get();

        // Tổng số dòng
        $totalRows = $data->sum('total');

        // Tính số dòng theo từng trạng thái
        $inProgressCount = $data->firstWhere('completed', ChanceConst::IN_PROGRESS)->total ?? 0;
        $successCount = $data->firstWhere('completed', ChanceConst::PROGRESS_SUCCESS)->total ?? 0;
        $failedCount = $data->firstWhere('completed', ChanceConst::PROGRESS_FAILED)->total ?? 0;

        return [
            'total' => $totalRows,
            'in_progress' => $inProgressCount,
            'progress_success' => $successCount,
            'progress_failed' => $failedCount,
        ];
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

    public function getListContactByID($id ){
        $account = $this->model->find($id);
        if ( empty ($account) ) {
            return ;
        }
        return $account->contacts;
    }

    public function updateStatus($id, $progress){
        $chance = $this->model->find($id);
        if($progress == ChanceConst::STEP_6){
            $chance->end_day =  Carbon::now()->startOfDay();
            $chance->save();
        }

        return $chance->update(['progress'=> $progress]);
    }

    public function updateProgress($id, $progress){
        $chance = $this->model->find($id);
        return $chance->update(['completed'=> $progress]);
    }
}
