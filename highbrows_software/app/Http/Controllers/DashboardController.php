<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Fee;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
{
    $this->middleware(['auth', 'admin'])->only('index');
}

    public function index(Request $request)
    {
        // Filters
        $month = $request->input('month');   // e.g. 04
        $year = $request->input('year');     // e.g. 2025
    
        // === INCOME ===
        $feeQuery = \App\Models\Fee::where('status', 'paid');
        if ($month) $feeQuery->whereMonth('created_at', $month);
        if ($year) $feeQuery->whereYear('created_at', $year);
        $totalcIncome = $feeQuery->sum('total_fee');
    
        $monthlyFeeQuery = \App\Models\MonthlyFee::where('status', 'paid');
        if ($month) $monthlyFeeQuery->whereMonth('created_at', $month);
        if ($year) $monthlyFeeQuery->whereYear('created_at', $year);
        $totalmIncome = $monthlyFeeQuery->sum('total_amount');
    
        $totalIncome = $totalcIncome + $totalmIncome;
    
        // === EXPENSES ===
        $expenseQuery = \App\Models\Expense::query();
        if ($month) $expenseQuery->whereMonth('created_at', $month);
        if ($year) $expenseQuery->whereYear('created_at', $year);
        $extraExpense = $expenseQuery->sum('total');
        $salaryQuery = DB::table('teachers')
        ->join('salaries', 'teachers.id', '=', 'salaries.teacher_id')
        ->where('salaries.status', 'paid');
    
    if ($month) {
        $salaryQuery->whereMonth('salaries.created_at', $month);
    }
    
    if ($year) {
        $salaryQuery->whereYear('salaries.created_at', $year);
    }
    
    $salaries = $salaryQuery->sum('teachers.salary');
    
        $totalExpenses = $extraExpense + $salaries;
        $revenue = $totalIncome - $totalExpenses;
    
        // === STUDENTS ===
        $studentQuery = \App\Models\Admission::query();
        if ($month) $studentQuery->whereMonth('created_at', $month);
        if ($year) $studentQuery->whereYear('created_at', $year);
        $studentCount = $studentQuery->count();
    
        // === EMPLOYEES ===
        $teacherQuery = \App\Models\Teacher::query();
        if ($month) $teacherQuery->whereMonth('created_at', $month);
        if ($year) $teacherQuery->whereYear('created_at', $year);
        $teacherCount = $teacherQuery->count();
    
        return view('admin.index', compact(
            'totalIncome',
            'totalcIncome',
            'totalmIncome',
            'extraExpense',
            'salaries',
            'totalExpenses',
            'revenue',
            'month',
            'year',
            'studentCount',
            'teacherCount'
        ));
    }

    public function students(){
        $studentcount=Admission::count();
        return view('admin.main',compact('studentcount'));
    }
    public function showIncome()
{
    $totalIncome = Fee::sum('total_fee');
    dd($totalIncome);
    return view('admin.index', compact('totalIncome'));
}

}
