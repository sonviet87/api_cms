<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalProject extends Model
{
    use HasFactory;

    protected $fillable = [];

    protected $guarded = [];

    protected $table = 'technical_project';
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
