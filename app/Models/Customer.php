<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Customer extends Model
{
    use HasFactory, Notifiable;

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'name',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    const UPDATED_AT = null;

    public function favorites()
    {
        return $this->belongsToMany(Course::class, 'customer_course')
            ->orderBy('id', 'desc');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
