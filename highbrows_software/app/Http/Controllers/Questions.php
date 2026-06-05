<?php

namespace App\Http\Controllers;
use App\Models\Question;
use Illuminate\Http\Request;

class Questions extends Controller
{
 // Show all questions
 public function index(Request $request)
 {
     $query = Question::query();

     if ($request->filled('grade')) {
         $query->where('grade', $request->grade);
     }

     if ($request->filled('subject')) {
         $query->where('subject', 'like', '%' . $request->subject . '%');
     }

     if ($request->filled('keyword')) {
         $query->where('question', 'like', '%' . $request->keyword . '%');
     }

     $questions = $query->latest()->get();

     return view('admin.questions', compact('questions'));
 }


 public function create()
 {
     return view('admin.questions_create');
 }
// Show form to edit a question
public function edit($id)
{
    $question = Question::findOrFail($id);
 
     // decode for editing
    return view('admin.questions_edit', compact('question'));
}

public function update(Request $request, $id)
{
    // Validate the incoming request
    $request->validate([
        'subject' => 'required|string',
        'grade' => 'required|string',
        'question' => 'required|string',
        'question_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // validate question image
        'option_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // validate option images
        'options' => 'nullable|required|array',
        'correct_answer' => 'required|string',
    ]);

    // Find the existing question by ID
    $question = Question::findOrFail($id);

    // Handle the question image upload if exists
    if ($request->hasFile('question_image')) {
        // Delete the old question image if it exists
        if ($question->question_image) {
            Storage::disk('public')->delete($question->question_image);
        }

        // Store the new question image
        $question_image_path = $request->file('question_image')->store('questions', 'public');
    } else {
        $question_image_path = $question->question_image; // Keep the old image if no new image is provided
    }

    // Handle the option images upload if they exist
    $option_images = $question->option_images ?? [];
    foreach (['A', 'B', 'C', 'D', 'E'] as $option) {
        if ($request->hasFile('option_images.' . $option)) {
            // Delete the old option image if it exists
            if (isset($option_images[$option])) {
                Storage::disk('public')->delete($option_images[$option]);
            }

            // Store the new option image
            $option_images[$option] = $request->file('option_images.' . $option)->store('options', 'public');
        }
    }

    // Update the question with the new data
    $question->update([
        'subject' => $request->subject,
        'grade' => $request->grade,
        'question' => $request->question,
        'question_image' => $question_image_path,
        'option_images' => $option_images, // Store the option images as JSON
        'options' => $request->options, // Store options as JSON
        'correct_answer' => $request->correct_answer,
    ]);

    return redirect()->route('questions.index')->with('success', 'Question updated successfully!');
}

// Destroy Method
public function destroy($id)
{
    // Find the existing question by ID
    $question = Question::findOrFail($id);

    // Delete the question image if it exists
    if ($question->question_image) {
        Storage::disk('public')->delete($question->question_image);
    }

    // Delete the option images if they exist
    if ($question->option_images) {
        foreach ($question->option_images as $option_image) {
            Storage::disk('public')->delete($option_image);
        }
    }

    // Delete the question record from the database
    $question->delete();

    return redirect()->route('questions.index')->with('success', 'Question deleted successfully!');
}


public function store(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'subject' => 'required|string',
        'grade' => 'required|string',
        'question' => 'required|string',
        'question_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // validate question image
        'option_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // validate option images
        'options' => 'nullable|required|array',
        'correct_answer' => 'required|string',
    ]);

    // Handle the question image upload if exists
    $question_image_path = null;
    if ($request->hasFile('question_image')) {
        $question_image_path = $request->file('question_image')->store('questions', 'public');
    }

    // Handle the option images upload if they exist
    $option_images = [];
    foreach (['A', 'B', 'C', 'D', 'E'] as $option) {
        if ($request->hasFile('option_images.' . $option)) {
            $option_images[$option] = $request->file('option_images.' . $option)->store('options', 'public');
        }
    }

    // Create the question
    $question = Question::create([
        'subject' => $request->subject,
        'grade' => $request->grade,
        'question' => $request->question,
        'question_image' => $question_image_path,
        'option_images' => $option_images, // Store option images as JSON
        'options' => $request->options, // Store options as JSON
        'correct_answer' => $request->correct_answer,
    ]);

    return redirect()->route('questions.index')->with('success', 'Question created successfully!');
}

}
