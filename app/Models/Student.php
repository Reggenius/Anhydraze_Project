<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Student extends Authenticatable
{
    use HasFactory;   

    protected $table = "students";
    protected $primaryKey = "student_id";
    protected $fillable = [
    'user_name',
    'password',
    'user_email',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public $timestamps = true;

    //Create a table relationship
    public function userResource(){
        return $this->belongsTo(UserResource::class, 'student_id', 'resource_id');
     }
}
