<?php
namespace App\Repositories;
use App\Constants\PermissionConst;
use App\Interfaces\AccountInterface;
use App\Models\Account;
use Illuminate\Support\Facades\Auth;


class AccountRepository implements AccountInterface {
    protected $model;
    function __construct(Account $account){
        $this->model = $account;
    }

    public function getList($filter=[]){
        $query = $this->model;
        if(!empty($filter)) {
            if (isset($filter['user_id']) && $filter['user_id'] != '') {
                $query = $query->where('user_id', $filter['user_id']) ;
            }
        }
        if (isset($filter['staffs']) && $filter['staffs'] != '') {

            $query = $query->orwhereIn('user_id',$filter['staffs']);
        }
        if (isset($filter['year']) && $filter['year'] != '') {
            $query = $query->whereYear('created_at', $filter['year'])->whereNull('is_new');
        }
        return $query->orderBy('id', 'desc')->get();
    }

    public function getAccountDashboard($filter = [])
    {
        $query = $this->model;
        $user = Auth::user();
        if ($user->hasPermissionTo(PermissionConst::IS_SALE)) {
            $filter['user_id'] = $user->id;
            $staffs = $user->subordinates()->get();
            if ($staffs) {
                $filter['staffs'] = $staffs->pluck('id')->toArray();;
            }
        }
        if (!empty($filter)) {
            if (isset($filter['user_id']) && $filter['user_id'] != '') {
                $query = $query->where('user_id', $filter['user_id']);
            }
            if (isset($filter['staffs']) && !empty($filter['staffs'])) {
                $query = $query->orWhereIn('user_id', $filter['staffs']);
            }
        }


        $totalAccounts = $query->count();

        $totalNewAccounts = $query->whereYear('created_at', now()->year)
            ->whereNull('is_new')
            ->count();

        $totalOldAccounts = $query->whereYear('created_at', now()->year)->WhereNotNull('is_new')
            ->count();

        return [
            'total_accounts' => $totalAccounts,
            'total_new_accounts' => $totalNewAccounts,
            'total_old_accounts' => $totalOldAccounts,
        ];
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
        if (isset($filter['staffs']) && $filter['staffs'] != '') {

            $query = $query->orwhereIn('user_id',$filter['staffs']);
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
