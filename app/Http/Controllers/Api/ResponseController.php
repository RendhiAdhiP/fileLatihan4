<?php

namespace App\Http\Controllers\Api;

use App\Models\Form;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Response;

class ResponseController extends Controller
{
    public function responseAnswer($slug, Request $request)
    {
        $form = Form::where('slug', $slug)->with('allowedDomain', 'question')->first();
        if ($form == null) {
            return response()->json(['messaage' => 'form nod found'], 404);
        }

        $request->validate([
            'question_id' => 'required|array',
            'value' => 'required_if:question_id,',
        ]);

        $form->response()->attach($request->user()->id, ['date' => now()]);

        $form_id = Response::where('form_id', $form->id)->first();

        $response = Response::find($form_id->id);

        $quest_id = collect($request->question_id);

        $quest_id->map(function ($c) use ($request, $response) {
            $response->answer()->attach(intval($c), ['value' => $request->value]);
        });

        $answr = $response->answer->map(function($a){
            return [
                'qid' => $a->pivot->question_id,
                'value' => $a->pivot->value,
            ];
        });
        // $answr = $response->with('answer')->get();
        
        // $answer = [
            
        // ]

        return response()->json($answr);;
    }
}
