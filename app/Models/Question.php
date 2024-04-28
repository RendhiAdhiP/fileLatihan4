<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $hidden = ['created_at','updated_at'];

    public function form(){
        return $this->belongsTo(Form::class, 'form_id','id');
    }

    
    public function answer(){
        return $this->belongsToMany(Response::class, 'answers', 'question_id','response_id')->withPivot(['value']);
    }

 
}
