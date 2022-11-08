<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
  use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    protected $guarded = [];

    protected $table = 'contact';

    public function users(){
        return $this->belongsTo(User::class,'user_id','id');

    }

    public function accounts(){
        return $this->belongsTo(Account::class,'account_id','id');
    }

}
