<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Debt extends Model
{
  use HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [];

    protected $guarded = [];

    protected $table = 'debts';

    public function fp()
    {
        return $this->belongsTo(FP::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
    protected static function booted()
    {
        static::created(function ($debt) {

            Account::where('id', $debt->fp->account_id)->whereNull('is_new')->update(['is_new' => now()->year]);
        });
    }

}
