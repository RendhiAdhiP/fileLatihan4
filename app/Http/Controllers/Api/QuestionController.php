<?php

namespace App\Http\Controllers\Api;

use App\Models\Form;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Question;

class QuestionController extends Controller
{
    public function addQuestion($slug, Request $request)
    {

        $req = $request->validate([
            'name' => 'required',
            'type' => 'required|in:short answer,paragraph,date,multiple choice,dropdown,checkboxes',
            'choices' => 'required_if:type,multiple choice,dropdown,multiple choice,checkboxes',
            'is_required' => 'required',
        ]);

        $form = Form::where('slug', $slug)->with('allowedDomain', 'question')->first();
        if ($form == null) {
            return response()->json(['messaage' => 'form nod found'], 404);
        }

        if(isset($req['choices'])){
            $req['choices'] = trim(json_encode($req['choices']),'[],"');
        }

        $q = Question::create([
            'name'=>$req['name'],
            'type'=>$req['type'],
            'choices'=>$req['choices'] ?? null,
            'is_required'=>$req['is_required'],
            'form_id'=>$form->id,
        ]);

        return response()->json(['message'=>'Add question success','question'=>$q],200);
    }


    public function delete($slug, $id)
    {
        $form = Form::where('slug', $slug)->with('allowedDomain', 'question')->first();
        if ($form == null) {
            return response()->json(['messaage' => 'form nod found'], 404);
        }

        $quest = $form->question->where('id',$id)->first();
        if($quest == null){
            return response()->json(['messaage' => 'question nod found'], 404);
        }
        $quest->delete();
        return response()->json(['messaage' => 'Remove question success'], 200);

    }

}
