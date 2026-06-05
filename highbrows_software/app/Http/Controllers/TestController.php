<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class TestController extends Controller
{


    public function generate(Request $request)
    {
        $request->validate([
            'grade' => 'required|integer',
            'subject' => 'required|string',
        ]);

        $questions = Question::where('grade', $request->grade)
            ->where('subject', $request->subject)
            ->inRandomOrder()
            ->take(5)
            ->get();

        return view('tests.take', compact('questions', 'request'));
    }



    public function submit(Request $request)
    {
        $answers = $request->input('answers');
        $correct = 0;

        foreach ($answers as $questionId => $selected) {
            $question = Question::find($questionId);
            if ($question && $question->correct_answer == $selected) {
                $correct++;
            }
        }

        $total = count($answers);
        $percentage = ($correct / $total) * 100;
        $result = $percentage >= 50 ? 'Pass' : 'Fail'; // Customize your criteria

        return view('tests.result', compact('correct', 'total', 'percentage', 'result'));
    }



}
