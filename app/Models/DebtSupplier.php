<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class DebtSupplier extends Model
{
  use HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    protected $guarded = [];

    protected $table = 'debt_suppliers';

    public function fp()
    {
        return $this->belongsTo(FP::class);
    }
    public function supplier()
    {
        return $this->belongsTo(Supplier::class)->withTrashed();;
    }

    protected static function booted()
    {
        static::created(function ($debtSupplier) {
            Supplier::where('id', $debtSupplier->supplier_id)->whereNull('is_new')->update(['is_new' => now()->year]);
        });
    }



}
