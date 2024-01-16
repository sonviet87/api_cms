<?php

namespace App\Services;



use App\Constants\RolePermissionConst;
use App\Interfaces\FPInterface;
use App\Interfaces\KpiInterface;
use App\Interfaces\KpiMemberGroupsInterface;
use App\Interfaces\ReportInterface;
use Illuminate\Support\Facades\Auth;


class KpiService extends BaseService
{

    protected $fp;
    protected $groupMember;
    function __construct(FPInterface $fp,KpiMemberGroupsInterface $groupMember)
    {
        $this->groupMember = $groupMember;
        $this->fp = $fp;

    }

    public function getList($filter)
    {

        if (isset($filter['startDay']) && $filter['startDay'] != '' && isset($filter['endDay']) && $filter['endDay'] != '') {
            $startDay = $filter['startDay'];
            $endDay = $filter['endDay'];
        }
        if(isset($filter['groupMember']) && $filter['groupMember'] !=''){
            $groupMember = $this->groupMember->getByID($filter['groupMember']);
            if($groupMember!=null){
                $users = $groupMember->users()->get()->pluck('id')->all();
                if(count($users)>0) $filter['users'] = $users;
            }
        }
        $rs =   $this->fp->getListbyUsers($filter);
        $totalMargin = $rs->sum('margin');

        $rs->put('total_salary', $totalMargin);

        return $rs;
    }



}
