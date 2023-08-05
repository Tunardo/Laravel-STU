<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name', 'last_name', 'email', 'date_of_birth', 'gender', 'address', 'phone',
    ];

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }
}
