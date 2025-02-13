<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KpiSetUpUser extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    protected $guarded = [];

    protected $table = 'kpi_setting_sale';

    public function customers()
    {
        return $this->hasMany(KpiCustomer::class,"group_id");
    }
    public function saleItems()
    {
        return $this->hasMany(KpiSettingSaleItem::class, 'kpi_setting_sale_id');
    }

    public function debtItems()
    {
        return $this->hasMany(KpiSettingDebtsItem::class, 'kpi_setting_sale_id');
    }

    public function staffManagers()
    {
        return $this->hasMany(KpiSettingStaffManager::class, 'kpi_setting_sale_id');
    }

    public function debts()
    {
        return $this->hasMany(KpiDebts::class,"group_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
