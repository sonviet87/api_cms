<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class FP extends Model
{
  use HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    protected $guarded = [];

    protected $table = 'fp';

    /*public function categories()
    {
        return $this->belongsToMany(Category::class,'category_fp','category_id','fp_id')->withPivot(["qty","price_buy"]);
    }*/

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

    public function fp_details()
    {
        return $this->hasMany(FPDetail::class,"fp_id");
    }

    public function supplier_fp_details($supplier_id)
    {
        return $this->hasMany(FPDetail::class,"fp_id")->where('supplier_id',$supplier_id);
    }

}
