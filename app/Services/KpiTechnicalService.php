<?php

namespace App\Services;

use App\Constants\DebtsConst;
use App\Interfaces\FPInterface;
use App\Interfaces\KpiSettingTechnicalInterface;
use App\Interfaces\KpiSettingTotalInterface;
use App\Interfaces\SysKpiInterface;
use App\Interfaces\TechnicalCertificateInterface;
use App\Interfaces\TechnicalProjectInterface;
use App\Interfaces\TechnicalReviewInterface;
use Carbon\Carbon;

class KpiTechnicalService extends BaseService
{
    protected $fp;
    protected $settingTotal;
    protected $kpiSettingTechnical;
    protected $technicalReview;
    protected $technicalCertificate;
    protected $technicalProject;
    protected $sysKpi;
    function __construct(FPInterface $fp,KpiSettingTechnicalInterface $kpiSettingTechnical,SysKpiInterface $sysKpi,KpiSettingTotalInterface $settingTotal,TechnicalCertificateInterface $technicalCertificate,TechnicalProjectInterface $technicalProject,TechnicalReviewInterface $technicalReview)
    {
        $this->sysKpi = $sysKpi;
        $this->settingTotal = $settingTotal;
        $this->kpiSettingTechnical = $kpiSettingTechnical;
        $this->technicalReview = $technicalReview;
        $this->technicalCertificate = $technicalCertificate;
        $this->technicalProject = $technicalProject;
        $this->fp = $fp;
    }

    public function getList($filter)
    {

        if(isset($filter['groupMember']) && $filter['groupMember'] !=''){

            $typeKpi = $filter['type'] ?? DebtsConst::MONTHS_1;

            $kpiSettingTechnical= $this->kpiSettingTechnical->getByID($filter['groupMember']);

            if($kpiSettingTechnical!=null){
                $total_percent_staff = 0;
                $total_points_staff = 0;
                $total_achievement_staff = 0;

                $userID = $kpiSettingTechnical->user_id;
                $filter['users'] = $userID;

                $technical_staff = $this->getStaffManager($filter,$kpiSettingTechnical);
                //dd($technical_staff);
                if(count($technical_staff)>0){


                    $total_points_staff = collect($technical_staff)->sum(function ($item) {
                        return $item['total_points'];
                    });

                    $total_achievement_staff = collect($technical_staff)->sum(function ($item) {
                        $percentage = $item['percent'];
                        $points = $item['total_points'];
                        return ($percentage * $points) / 100;
                    });
                }

                $technical_certificate = $this->getCertificateKpi($filter,$kpiSettingTechnical);
                $technical_project = $this->getProjectKPI($filter,$kpiSettingTechnical);
                $technical_review = $this->getReviewKPI($filter,$kpiSettingTechnical);
                $total_percent = $technical_certificate['technical_certificate_condition']['percentage'] + $technical_project['project_certificate_condition']['percentage'] + $technical_review['review_certificate_condition']['percentage'] + $total_percent_staff;
                $total_points = $technical_certificate['technical_certificate_condition']['points'] + $technical_project['project_certificate_condition']['points'] + $technical_review['review_certificate_condition']['points'] + $total_points_staff;


                $total_achievement_certificate = ($kpiSettingTechnical->certificate_percent/100)*$technical_certificate['technical_certificate_condition']['points'];

                $total_achievement_project = ($kpiSettingTechnical->project_percent/100)*$technical_project['project_certificate_condition']['points'];

                $total_achievement_review = ($kpiSettingTechnical->review_percent/100)*$technical_review['review_certificate_condition']['points'];

                $total_achievement = $total_achievement_certificate + $total_achievement_project+$total_achievement_review+$total_achievement_staff;
                //dd($technical_project);

                $kpiTotalPersonal = $this->getKpiSettingsYear($total_achievement,$filter,'technical');

                //get kpi company

                $rsYear = $this->sysKpi->getByID(1);
                $targetKpiYear = $rsYear->kpi_company;
                $filteryear = $filter;
                $filteryear['startDay'] = Carbon::create($filter['selectedYear'])->startOfYear();
                $filteryear['endDay'] = Carbon::create($filter['selectedYear'])->endOfYear();
                unset($filteryear['users']);

                $rsListYear = $this->fp->getListbyUsers($filteryear);
                $totalSellingYear = $rsListYear->sum('selling');
                $percent_year = round(($totalSellingYear/$targetKpiYear)*100,);
                $settingCompany = $this->getKpiSettingsCompany($percent_year, $filter,'company');

                $salary = $kpiSettingTechnical->user->salary->salary;

                $totalAllBouns = 0;
                if($kpiTotalPersonal['bonus'] <1 ||  $settingCompany['bonus'] <1){
                    $totalAllBouns = ($kpiTotalPersonal['bonus']*  $salary) + ($settingCompany['bonus']*  $salary);
                }
                else {
                    $totalAllBouns = $kpiTotalPersonal['bonus'] *  $settingCompany['bonus'] * $salary;

                }
                return [
                    'salary' =>$salary,
                    'total_selling_year' =>$totalSellingYear,
                    'technical_certificate' => $technical_certificate,
                    'technical_project' => $technical_project,
                    'technical_review' => $technical_review,
                    'technical_staff_manager' => $technical_staff,
                    'total_percent' => $total_percent,
                    'total_points' => $total_points,
                    'total_achievement' => $total_achievement,
                    'kpi_personnal' =>  collect($kpiTotalPersonal)->only(['name', 'points', 'bonus']),
                    'target_company' =>  $targetKpiYear,
                    'kpi_company' => collect($settingCompany)->only(['name', 'points', 'bonus']),
                    'totalBouns' => $totalAllBouns,
                    'certificate_percent' => $kpiSettingTechnical->certificate_percent,
                    'project_percent' => $kpiSettingTechnical->project_percent,
                    'review_percent' => $kpiSettingTechnical->review_percent,

                ];
            }



        }
        return $this->_result(false, 'Không thể tạo kpi');
    }

    private function getStaffManager($filter,$kpiSetUpUser )
    {
        $type = $filter['type'] ?? DebtsConst::MONTHS_12;

        $resultData = [];

        if ($kpiSetUpUser) {
            $filteredStaffManagers = $kpiSetUpUser->staffManagers->filter(function ($manager) use ($type) {

                return $manager->type === $type . 'months' && $manager->kpi_type == 'technical' ;

            });
           // dd($filteredStaffManagers);
            foreach ($filteredStaffManagers as $manager) {
                if($manager->user_id == null) continue;
                $user_name = $manager->user->name;
                $filterStaff = $filter;
                $filterStaff['users'] = $manager->user_id;
                $kpiUser = $this->kpiSettingTechnical->getByUserID($manager->user_id)->first();

                $total = $this->getAchievementUser($kpiUser,$filterStaff);
                $resultData[] = [
                    'user_id' => $manager->user_id,
                    'percent' => $manager->percent,
                    'user_name' => $user_name,
                    'total_points' =>$total,

                ];
            }
            /*foreach ($filteredStaffManagers as $manager) {
                if($manager->user_id == null) continue;
                $user_name = $manager->user->name;
                $filterStaff = $filter;
                $filterStaff['users'] = $manager->user_id;
                $kpiUser = $this->kpiSettingTechnical->getByUserID($manager->user_id)->first();

                $total = $this->getAchievementUser($kpiUser,$filterStaff);
               // dd($total);
                $result = $manager->staffConditions
                    ->where('number', '<=', $total['points'])
                    ->sortByDesc('number')
                    ->first();

                if (!$result) {
                    $maxCondition = $manager->staffConditions->sortByDesc('number')->first();
                    $minCondition = $manager->staffConditions->sortBy('number')->first();

                    if ($total['points'] < $minCondition->number) {
                        $result = collect(['number' => 0, 'percentage' => 0, 'points' => 0]);
                    } else {
                        $result = $maxCondition;
                    }
                }
                //dd($result);
                $result = $result->only(['number', 'percentage', 'points']);
                $resultData[] = [
                    'user_id' => $manager->user_id,
                    'user_name' => $user_name,
                    'total_points' =>$total['points'],
                    'kpi' => $result
                ];
            }*/
        }
        return $resultData;
    }

    protected function getAchievementUser($kpi,$filter){
        if($kpi == null) return 0;
        $technical_certificate = $this->getCertificateKpi($filter,$kpi);
        $technical_project = $this->getProjectKPI($filter,$kpi);
        $technical_review = $this->getReviewKPI($filter,$kpi);

        $achievement_cer = ($kpi->certificate_percent/100) * $technical_certificate['technical_certificate_condition']['points'];
        $achievement_project = ($kpi->project_percent/100) *$technical_project['project_certificate_condition']['points'];
        $achievement_review = ($kpi->review_percent/100)*$technical_review['review_certificate_condition']['points'];
        $totalAchivement =  $achievement_cer+$achievement_project+$achievement_review;

        $kpiTotal = $this->getKpiSettingsYear($totalAchivement,$filter,'technical');

        return $kpiTotal['points'];
    }

    private function getKpiSettingsYear($totalGoals, $filter,$type)
    {
        $totalGoals = floor($totalGoals);
        $typeKpi = $filter['type'] ?? DebtsConst::MONTHS_12;
        $conditionsSettings = $this->settingTotal->getList();
        $conditionsSettingsType = $conditionsSettings->filter(function ($item) use ($typeKpi,$type) {
            return $item['type'] === $typeKpi . 'months' && $item['type_kpi'] === $type;
        });

        if ($conditionsSettingsType->isEmpty()) {
            return ['bonus' => 0, 'points' => 0, 'name' => 'Không đạt'];
        }

        $sortedConditions = $conditionsSettingsType->sortBy('points');

        $minCondition = $sortedConditions->first();
        $maxCondition = $sortedConditions->last();

        if ($totalGoals <= 0 || $totalGoals < $minCondition['points']) {
            return ['bonus' => 0, 'points' => 0, 'name' => 'Không đạt'];
        }

        if ($totalGoals > $maxCondition['points']) {
            return $maxCondition;
        }

        return $sortedConditions->firstWhere('points', $totalGoals)
            ?: $sortedConditions
                ->where('points', '<', $totalGoals)
                ->sortByDesc('bonus')
                ->first();
    }
    /**
     * Finds the appropriate condition for a value from a list of conditions.
     *
     * @param int|float $value The value to compare against the conditions.
     * @param array $conditions The list of conditions to check.
     * @param array $defaultCondition The default condition to return if no match is found.
     * @param string $comparisonKey The key in the condition array to compare against the value.
     * @return array The matching condition or the default condition.
     */
    protected function getMatchingCondition($value, $conditions, $defaultCondition, $comparisonKey)
    {
        // Return default if conditions are empty, invalid, or value is 0
        if (!$conditions || !is_array($conditions) || $value == 0) {
            return $defaultCondition;
        }

        $maxCondition = null;

        foreach ($conditions as $condition) {
            // Update maxCondition if not set or condition's value is greater
            if (!$maxCondition || $condition[$comparisonKey] > $maxCondition[$comparisonKey]) {
                $maxCondition = $condition;
            }

            // Return condition immediately if it matches the value
            if (isset($condition[$comparisonKey]) && $condition[$comparisonKey] == $value) {
                return $condition;
            }
        }

        // Return maxCondition if value is greater than maxCondition's comparisonKey value
        if ($value > $maxCondition[$comparisonKey]) {
            return $maxCondition;
        }

        // Return default condition if no match
        return $defaultCondition;
    }

    /**
     * Retrieves certificate KPI based on the user's filter and KPI technical settings.
     *
     * @param array $filter The filter criteria for fetching the user's certificates.
     * @param object $kpiSettingTechnical The technical KPI settings containing certificate conditions.
     * @return array An array with technical summary and matching certificate conditions.
     */
    protected function getCertificateKpi($filter, $kpiSettingTechnical)
    {
        ['summary' => $summary] = $this->technicalCertificate->getListbyUsers($filter);

        $certificateConditions = $kpiSettingTechnical->certificate_conditions ?? [];
        $defaultCondition = ['number' => 0, 'percentage' => 0, 'points' => 0];

        $goals2 = $summary['goals_2'] ?? 0;

        $matchingCondition = $this->getMatchingCondition($goals2, $certificateConditions, $defaultCondition, 'number');

        return [
            'technical_summary' => $summary,
            'technical_certificate_condition' => $matchingCondition
        ];
    }

    /**
     * Retrieves review KPI based on the user's filter and KPI technical settings.
     *
     * @param array $filter The filter criteria for fetching the user's reviews.
     * @param object $kpiSettingTechnical The technical KPI settings containing review conditions.
     * @return array An array with review points and matching review conditions.
     */
    protected function getReviewKPI($filter, $kpiSettingTechnical)
    {
        $pointsProject = $this->technicalReview->getLowestPointByUsers($filter);

        $reviewConditions = $kpiSettingTechnical->review_conditions ?? [];
        $defaultCondition = ['number' => 0, 'percentage' => 0, 'points' => 0];

        // Use `floor` for the comparison value
        $matchingCondition = $this->getMatchingCondition(floor($pointsProject), $reviewConditions, $defaultCondition, 'points');

        return [
            'review_points' => $pointsProject,
            'review_certificate_condition' => $matchingCondition
        ];
    }

    /**
     * Retrieves project KPI based on the user's filter and KPI technical settings.
     *
     * @param array $filter The filter criteria for fetching the user's projects.
     * @param object $kpiSettingTechnical The technical KPI settings containing project conditions.
     * @return array An array with project points and matching project conditions.
     */
    protected function getProjectKPI($filter, $kpiSettingTechnical)
    {
        $pointsProject = $this->technicalProject->getLowestPointByUsers($filter);

        $projectConditions = $kpiSettingTechnical->project_conditions ?? [];
        $defaultCondition = ['number' => 0, 'percentage' => 0, 'points' => 0];

        $matchingCondition = $this->getMatchingCondition($pointsProject, $projectConditions, $defaultCondition, 'points');

        return [
            'project_points' => $pointsProject,
            'project_certificate_condition' => $matchingCondition
        ];
    }

    private function getKpiSettingsCompany($totalGoals,$filter,$kpi_type){

        $typeKpi = $filter['type'] ?? DebtsConst::MONTHS_12;
        $conditionsSettings = $this->settingTotal->getList();
        $conditionsSettingsType = $conditionsSettings->filter(function ($item) use ($typeKpi,$kpi_type){
            return $item['type'] === $typeKpi.'months' && $item['type_kpi'] === $kpi_type;
        });

        // find condtion
        $matchedCondition = $conditionsSettingsType->first(function ($item) use ($totalGoals) {
            $min = $item['min'] != null ? (float)$item['min'] : null;
            $max = $item['max'] != null ? (float)$item['max'] : null;

            return ($totalGoals >= $min) &&  ($totalGoals <= $max);
        });


        // if exist
        if ($matchedCondition)  return  $matchedCondition;

        // if not exist, compare min và max
        $minCondition = $conditionsSettingsType->sortBy('min')->first();
        $maxCondition = $conditionsSettingsType->sortByDesc('max')->first();

        if ($minCondition && $totalGoals < (float)$minCondition['min']) {
            return  ['bonus' => 0, 'points' => 0,'name' => 'Không đạt'];
        }

        if ($maxCondition && $totalGoals > (float)$maxCondition['max']) {
            return $maxCondition;
        }

        return  ['bonus' => 0, 'points' => 0,'name' => 'Không đạt'];

    }



}
