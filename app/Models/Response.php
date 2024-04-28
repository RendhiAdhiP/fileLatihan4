<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function answer(){
        return $this->belongsToMany(Question::class, 'answers', 'response_id','question_id')->withPivot(['value','question_id']);
    }
}
