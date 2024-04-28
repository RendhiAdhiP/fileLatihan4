<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $hidden = ['created_at','updated_at'];

    public function creator(){
        return $this->belongsTo(Form::class, 'creator_id','id');
    }

    public function question(){
        return $this->hasMany(Question::class, 'form_id','id');
    }

    public function allowedDomain(){
        return $this->hasMany(AllowedDomain::class, 'form_id','id');
    }

    public function response(){
        return $this->belongsToMany(User::class, 'responses', 'form_id','user_id')->withPivot(['date']);
    }


}
