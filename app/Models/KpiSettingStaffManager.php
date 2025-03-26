<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KpiSettingStaffManager extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    protected $guarded = [];

    protected $table = 'kpi_setting_staff_manager';
    public function staffConditions()
    {
        return $this->hasMany(KpiSettingStaffManagerItem::class, 'kpi_setting_staff_id');
    }


    public function sale()
    {
        return $this->belongsTo(KpiSetUpUser::class, 'kpi_setting_sale_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

}
