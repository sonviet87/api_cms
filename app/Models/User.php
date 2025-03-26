<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,HasRoles, SoftDeletes;

    protected $guard_name = 'api';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'phone',
        'role_id',
        'salary_lv_id',
        'position_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'config' => 'array',
    ];

    public function fpsAsTechnical()
    {
        return $this->belongsToMany(Fp::class, 'fp_technical', 'user_id', 'fp_id');
    }

    public function salary()
    {
        return $this->belongsTo(Salary::class,'salary_lv_id')->withTrashed();
    }

    public function groups()
    {
        return $this->belongsToMany(KpiMemberGroup::class, 'users_member_group', 'user_id', 'group_id');
    }


    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function subordinates()
    {
        return $this->belongsToMany(User::class, 'subordinates', 'manager_id', 'user_id');
    }


    public function manager()
    {
        return $this->hasOne(Subordinate::class, 'user_id');
    }

    public function kpi(){
        return $this->belongsTo(KpiSetUpUser::class)();
    }

}
