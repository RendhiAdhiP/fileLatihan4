<?php

namespace App\Http\Controllers\Api;

use App\Models\Form;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AllowedDomain;


class FormController extends Controller
{
    public function createForm(Request $request)
    {
        // auth()->user()->tokens()->delete();

        $validate = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'limit_one_response' => 'required',
            'slug' => 'required|unique:forms,slug|regex:/^[a-zA-z.-]+/',
            'allowed_domain' => 'array',
        ]);

        $form = Form::create([
            'name' => $validate['name'],
            'description'=>$validate['description'],
            'limit_one_response'=>$validate['limit_one_response'],
            'slug'=>$validate['slug'],
            'creator_id'=>$request->user()->id,
        ]);

        if(isset($validate['allowed_domain'])){
             return $request->allowed_domain->map(function($a) use($form){
                AllowedDomain::create([
                    'form_id'=>$form->id,
                    'allowed_domain'=>$a->allowed_domain,
                ]);
            });
        }else{
            $validate['allowed_domain'] = null;  
        }

        $f = [
            'name'=>$form->name,
            'slug'=>$form->slug,
            'description'=>$form->description,
            'limit_one_response'=>$form->limit_one_response,
            'creator_id'=>$form->creator_id,
            'id'=>$form->id,
        ];

        return response()->json(['messaage' => 'success creaate form','form'=>$f], 200);
    }

    public function getAllForm(Request $request)
    {
        $form = Form::where('creator_id',$request->user()->id)->get();

        return response()->json(['messaage' => 'Get all forms success','form'=>$form], 200);

    }

    public function getDetail($slug,Request $request)
    {
        $form = Form::where('slug',$slug)->with('allowedDomain','question')->first();
        if($form == null){
            return response()->json(['messaage' => 'form nod found'], 404);

        }

        $f = [
            'id'=>$form->id,
            'name'=>$form->name,
            'slug'=>$form->slug,
            'description'=>$form->description,
            'limit_one_response'=>$form->limit_one_response,
            'creator_id'=>$form->creator_id,
            'allowed_domains'=>$form->allowedDomain->map(function($a){
                return $a->pluck('domain');
            }),
            'question'=>$form->question
        ];


        return response()->json(['messaage' => 'Get form success','form'=>$f], 200);

    }
}
