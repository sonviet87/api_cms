<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Supplier extends Model
{
  use HasFactory, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [ ];

    protected $guarded = [];

    protected $table = 'supplier';

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function details()
    {
        return $this->belongsTo(FPDetail::class)->withTrashed();
    }

}
