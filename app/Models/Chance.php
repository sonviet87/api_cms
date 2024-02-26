<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Chance extends Model
{
  use HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    protected $guarded = [];

    protected $table = 'chances';

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function userAssign()
    {
        return $this->belongsTo(User::class,"user_assign","id")->withTrashed();
    }

    public function account()
    {
        return $this->belongsTo(Account::class)->withTrashed();
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class)->withTrashed();
    }

}
