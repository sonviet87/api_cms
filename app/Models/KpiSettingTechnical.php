<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KpiSettingTechnical extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    protected $guarded = [];

    protected $table = 'kpi_setting_technical';
    protected $casts = [
        'certificate_conditions' => 'array',
        'project_conditions' => 'array',
        'review_conditions' => 'array',
    ];

    public function staffManagers()
    {
        return $this->hasMany(KpiSettingStaffManager::class, 'kpi_setting_sale_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
