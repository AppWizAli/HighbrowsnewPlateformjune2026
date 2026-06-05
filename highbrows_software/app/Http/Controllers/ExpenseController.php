<?php
namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ExpenseController extends Controller
{
    // Display list of expenses with optional month-year filter
    public function index(Request $request)
    {
        $query = Expense::query();

        if ($request->has('year') && !empty($request->year)) {
            $query->whereYear('date', $request->year);
        }

        // Filter by month if it's provided
        if ($request->has('month') && !empty($request->month)) {
            $query->whereMonth('date', $request->month);
        }

        $expenses = $query->latest()->paginate(10);
        return view('admin.expenses.index', compact('expenses'));
    }

    // Show form to create a new expense
    public function create()
    {
        return view('admin.expenses.create');
    }

    // Store a new expense
    public function store(Request $request)
    {
        $request->validate([
            'expenses' => 'required|array|min:1',
            'expenses.*.description' => 'required|string|max:255',
            'expenses.*.amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        $total = collect($request->expenses)->sum('amount');

        $details = $request->expenses; // this is the array of expense items
// dd($details);
        Expense::create([
            'details' => $details,
            'total' => $total,
            'date' => $request->date,
        ]);

        return redirect()->route('expenses.index')->with('success', 'Expense added successfully.');

    }

    // Show form to edit an existing expense
    public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        return view('admin.expenses.edit', compact('expense'));
    }

    // Update an existing expense
    public function update(Request $request, $id)
    {
        $request->validate([
            'expenses' => 'required|array|min:1',
            'expenses.*.description' => 'required|string|max:255',
            'expenses.*.amount' => 'required|numeric|min:0',
            'date' => 'required|date',
        ]);

        $expense = Expense::findOrFail($id);

        $total = collect($request->expenses)->sum('amount');

        $expense->update([
            'details' => $request->expenses, // saving 'expenses' array into 'details' column
            'total' => $total,
            'date' => $request->date,
        ]);


        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function uploadReceipt(Request $request, $expenseId)
    {
        $request->validate([
            'image' => 'required|mimes:jpg,png,pdf|max:2048',
        ]);

        $installment = Expense::findOrFail($expenseId);

        $fileName = time().'_'.$request->file('image')->getClientOriginalName();
        $request->file('image')->storeAs('public/expenses', $fileName);


        $installment->image = 'expenses/'.$fileName;
        $installment->update();


        return back()->with('message', 'Receipt uploaded successfully.');
    }
    // Delete an expense
    public function destroy($id)
    {
        Expense::findOrFail($id)->delete();
        return redirect()->route('expenses.index')->with('success', 'Expense deleted.');
    }
}
