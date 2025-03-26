<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KpiMemberGroup extends Model
{
    use HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    protected $guarded = [];

    protected $table = 'kpi_member_groups';

    public function customers()
    {
        return $this->hasMany(KpiCustomer::class,"group_id");
    }

    public function debts()
    {
        return $this->hasMany(KpiDebts::class,"group_id");
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_member_group', 'group_id', 'user_id');
    }
}
