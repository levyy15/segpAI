<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ic',
        'address',
        'hospital_id',
        'role',
        'contact',
        'username',
        'password',
    ];
}
