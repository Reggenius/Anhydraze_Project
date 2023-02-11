<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserResource extends Model
{
    use HasFactory;

    protected $table = "user_resources";

    protected $primaryKey = "resource_id";

    protected $fillable = [
    'student_id',
    'event_name',
    'event_date',
    'event_time',
    'event_description',
    'img_url',
    'img_description',
    'note_url',
    'note_description',
    ];

    public $timestamps = true;

    public function student(){
        return $this->hasMany(Student::class, 'student_id', 'student_id');
    }
}
