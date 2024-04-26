<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use MattDaneshvar\Survey\Models\Entry;
use MattDaneshvar\Survey\Models\Survey;

class SurveyController extends Controller
{
    public function index(Request $request)
    {
    }

    public function edit(Survey $survey)
    {
//        (new Entry)->for($survey)->by(\Auth::user())->fromArray([
//            'q1' => 5,
//            'q2' => 'elo',
//            'q3' => 'Yes',
//        ])->push();
        return view('surveys.edit', compact('survey'));
    }

    public function submitAnswer(Survey $survey, Request $request)
    {
        $user = Auth::user();
        if (!$user->student->surveys->contains($survey)) {
            return redirect()->route('survey-users');
        }

        (new Entry)->for($survey)->by($user->student)->fromArray($request->except('_token'))->push();

        return redirect()->route('survey-users');
    }
}
