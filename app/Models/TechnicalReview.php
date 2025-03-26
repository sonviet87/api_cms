<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicalReview extends Model
{
    use HasFactory;
    protected $fillable = [];

    protected $guarded = [];

    protected $table = 'technical_review';

    protected $casts = [
        'data' => 'array',
    ];
    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}
