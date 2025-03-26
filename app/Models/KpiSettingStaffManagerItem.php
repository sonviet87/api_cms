<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KpiSettingStaffManagerItem extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    protected $guarded = [];

    protected $table = 'kpi_setting_staff_manager_item';

    public function staffManager()
    {
        return $this->belongsTo(KpiSettingStaffManager::class, 'kpi_setting_staff_id');
    }
}
