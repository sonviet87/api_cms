<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FPUser extends Model
{
    use HasFactory;
    protected $fillable = [];

    protected $guarded = [];

    protected $table = 'fp_user';

}
