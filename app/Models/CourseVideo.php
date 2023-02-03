<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseVideo extends Model
{
    protected $guarded = [
        'id',
    ];

    public $timestamps = false;


    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
