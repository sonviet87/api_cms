<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class FPDetail extends Model
{
  use HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    protected $guarded = [];

    protected $table = "fp_details";

    public function fp()
    {
        return $this->belongsTo(FP::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class)->withTrashed();;
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class,'supplier_id')->withTrashed();;
    }
}
