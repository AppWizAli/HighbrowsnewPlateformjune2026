<?php

namespace App\Http\Controllers;
use App\Models\Salarie;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SalariesController extends Controller
{
    public function index()
    {

        $salaries = Salarie::with('teacher')->paginate(10);

        return view('admin.employees-salaries', compact('salaries'));
    }


public function createForCurrentMonth(Request $request)
{
    $currentDate = Carbon::now();
    $currentMonthStart = $currentDate->copy()->startOfMonth()->toDateString();
    $currentMonthEnd = $currentDate->copy()->endOfMonth()->toDateString();

    // Check if any salary record exists for this month
    $existingSalaries = Salarie::whereBetween('date', [$currentMonthStart, $currentMonthEnd])->exists();

    if ($existingSalaries) {
        return redirect()->back()->with('message', 'Salaries for this month are already created.');
    }

    $teachers = Teacher::all();

    foreach ($teachers as $teacher) {
        Salarie::create([
            'teacher_id' => $teacher->id,
            'date' => $currentDate->toDateString(), // Use full date
            'status' => 'unpaid',
        ]);
    }

    return redirect()->route('salary.index')->with('message', 'Salaries for the current month have been created successfully!');
}


    public function paySalary($id)
{

    $salary = Salarie::findOrFail($id);


    $salary->update([
        'status' => 'paid',
        'payment_date' => Carbon::now(),
    ]);

    return redirect()->route('salary.index')->with('message', 'Salary marked as paid successfully!');
}
public function generateReceipt($id)
{

    $salary = Salarie::with('teacher')->findOrFail($id);


    if ($salary->status === 'unpaid') {
        return redirect()->back()->with('error', 'Cannot generate receipt for unpaid salary.');
    }


    return view('admin.salary-receipt', compact('salary'));
}

}
