<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
  use HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    protected $guarded = [];

    protected $table = 'contact';

    public function users(){
        return $this->belongsTo(User::class,'user_id','id')->withTrashed();

    }

    public function accounts(){
        return $this->belongsTo(Account::class,'account_id','id')->withTrashed();
    }

}
