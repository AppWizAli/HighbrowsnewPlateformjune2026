<?php

namespace App\Http\Controllers;
use App\Models\Admission;
use App\Models\MonthlyFee;
use App\Models\Clase;
use App\Models\College;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class StudentMonthlyFee extends Controller
{
public function index(Request $request)
{
$user_id = Auth::id();
$admission = Admission::where('user_id', $user_id)->first();

$query = MonthlyFee::with(['student', 'details'])
    ->where(function ($builder) use ($user_id, $admission) {
        $builder->where('user_id', $user_id);

        if ($admission) {
            $builder->orWhere('student_id', $admission->id);
        }
    });

    if ($request->has('month') && !empty($request->month)) {
        $query->whereMonth('date', $request->month);
    }

    if ($request->has('year') && !empty($request->year)) {
        $query->whereYear('date', $request->year);
    }

    $monthlyFees = $query->get();

    return view('student.monthlyfee', compact('monthlyFees'));
}


    public function feeDetails()
    {
        $userId = Auth::id();
        $admission = Admission::where('user_id', $userId)->first();

        $monthlyFees = MonthlyFee::with('details')
            ->where(function ($query) use ($userId, $admission) {
                $query->where('user_id', $userId);

                if ($admission) {
                    $query->orWhere('student_id', $admission->id);
                }
            })
            ->get();

        return view('student.monthlyfee', compact('monthlyFees'));
    }
    public function uploadReceipt(Request $request, $installmentId)
    {
        $request->validate([
            'receipt' => 'required|mimes:jpg,png,pdf|max:2048',
        ]);

        $installment = MonthlyFee::findOrFail($installmentId);

        $fileName = time().'_'.$request->file('receipt')->getClientOriginalName();
        $request->file('receipt')->storeAs('public/receipts', $fileName);


        $installment->receipt = $fileName;
        $installment->save();


        return back()->with('message', 'Receipt uploaded successfully.');
    }

    public function statusupdated($id)
    {
        $monthlyfee = MonthlyFee::findOrFail($id);

        $monthlyfee->updatePaymentStatus(); // Assuming this method exists and does what's needed

        return response()->json([
            'success' => true,
            'status' => $monthlyfee->status, // Optional: return status
            'message' => 'Status updated successfully.'
        ]);
    }
    public function statusupdate(Request $request, $id)
{
    $request->validate([
        'receiving_date' => 'required|date',
        'receiver_name' => 'required|string|max:255',
    ]);

    $monthlyfee = MonthlyFee::findOrFail($id);

    // Assuming updatePaymentStatus() marks the status as 'paid'
    $monthlyfee->status = 'paid';
    $monthlyfee->receiving_date = $request->receiving_date;
    $monthlyfee->receiver_name = $request->receiver_name;
    $monthlyfee->save();

    return response()->json([
        'success' => true,
        'status' => $monthlyfee->status,
        'message' => 'Status updated successfully.',
    ]);
}


}
