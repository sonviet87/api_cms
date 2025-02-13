<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class KpiSettingTechnicalResource extends JsonResource
{
    public function toArray($request)
    {

        $staffManagers = $this->staffManagers->groupBy('type');
        $date = Carbon::create($this->year, 1, 1, 0, 0, 0, 'UTC');
        return [
            'name' => $this->name,
            'user_id' => $this->user_id,
            'year' => $date,
            'certificate_conditions' => $this->certificate_conditions,
            'project_conditions' => $this->project_conditions,
            'review_conditions' => $this->review_conditions,
            'kpi_condition_staff_year' => $staffManagers->count() >0 ? $this->transformStaffManagers($staffManagers['12months'] ?? []) : [],
        ];
    }

    private function transformStaffManagers($staffManagers)
    {
        return $staffManagers->map(function ($manager) {
            return [
                'user_id' => $manager->user_id,
                'staff_conditions' => $manager->staffConditions
                    ->sortBy('number')
                    ->map(function ($condition) {
                        return [
                            'number' => $condition->number,
                            'percentage' => $condition->percentage,
                            'type' => $condition->type,
                            'points' => $condition->points,
                        ];
                    })
                    ->values(),
            ];
        });
    }


    public function with($request)
    {
        return [
            'status' => true,
        ];
    }
}
