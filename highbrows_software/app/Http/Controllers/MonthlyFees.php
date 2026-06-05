<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Fee;
use App\Models\User;
use App\Models\MonthleyFee;
use App\Models\MonthlyFee;
use App\Models\MonthlyFeeDetail;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;
class MonthlyFees extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::where('usertype', 'user')
        ->when($request->filled('grade'), function ($query) use ($request) {
            $query->where('grade', $request->grade);
        })
        ->when($request->filled('category'), function ($query) use ($request) {
            $query->where('category', $request->category);
        })
            ->with([
                'monthlyFeesAsUser' => function ($q) use ($request) {
                    if ($request->filled('month')) {
                        $q->whereMonth('date', $request->month);
                    }
                    if ($request->filled('year')) {
                        $q->whereYear('date', $request->year);
                    }
                },
                'monthlyFeesAsStudent' => function ($q) use ($request) {
                    if ($request->filled('month')) {
                        $q->whereMonth('date', $request->month);
                    }
                    if ($request->filled('year')) {
                        $q->whereYear('date', $request->year);
                    }
                }
            ])
            ->get();

        // Combine all monthly fees from both relationships into a single collection
        $monthlyFees = $users->flatMap(function ($user) {
            return $user->monthlyFeesAsUser->merge($user->monthlyFeesAsStudent);
        });

        return view('fees.show', compact('users', 'monthlyFees'));
    }





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()

    {
        $students = Admission::all();
        $users=User::where('usertype', 'user')->doesntHave('admissions')->doesntHave('monthlyFeesAsUser')->get();
        // dd(numberToWords(45667));
        return view('fees.add',compact('students','users'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {

    // dd($request);
        $request->validate([
            'student_id' => ['required', function ($attribute, $value, $fail) {
                $hasAdmission = Admission::where('user_id', $value)->exists();
                $isUser = User::where('id', $value)->exists();

                if (! $hasAdmission && ! $isUser) {
                    $fail('The selected student is invalid.');
                }
            }],
            'date' => 'required|date',
            'receiving_date' => 'nullable|date',
            'fee_month' => 'required',
            'discount' => 'nullable|numeric|min:0|max:100',
            'discounted_tuition_fee' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'amount_words' => 'nullable|string',
            'receiver_name' => 'nullable|string|max:255',
            'generated_by' => 'required|string|max:255',
            'fees' => 'nullable|array',
            'fees.*.description' => 'nullable|string',
            'fees.*.amount' => 'nullable|numeric|min:0',
        ]);

        // Get the last receipt number
        $lastReceipt = DB::table('monthly_fees')->latest('id')->first();

        // Generate new receipt number
        if ($lastReceipt) {
            $lastNumber = (int) str_replace('REC-', '', $lastReceipt->receipt_no);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        $receiptNumber = 'REC-' . str_pad($newNumber, 5, '0', STR_PAD_LEFT);

        $studentId = $request->input('student_id');
        $user =User::find($studentId);
        $admission = Admission::where('user_id', $studentId)->first();

        $data = [
            'date' => $request->date,
            'receipt_no' => $receiptNumber, // Ensure you have a valid receipt number logic
            'receiving_date' => $request->receiving_date,
            'fee_month' => $request->fee_month,
            'discount' => $request->discount ?? 0,
            'discounted_tuition_fee' => $request->discounted_tuition_fee ?? 0,
            'total_amount' => $request->total_amount,
            'amount_words' => $request->amount_words ?? null,
            'receiver_name' => $request->receiver_name ?? null,
            'generated_by' =>$request->generated_by??  auth()->user()->name,
        ];
        // If it's a user, store the ID in user_id
        if (!$admission) {
            $data['user_id'] = $user->id;
        } else {
            $data['student_id'] = $admission->id;
        }
        // dd($data['user_id']);

        // Create the new MonthlyFee record
        $monthlyFee = MonthlyFee::create($data);

            if (!empty($request->fees)) {
                foreach ($request->fees as $fee) {

                    if (!empty($fee['description']) && isset($fee['amount'])) {
                        MonthlyFeeDetail::create([
                            'monthly_fee_id' => $monthlyFee->id,
                            'description' => $fee['description'],
                            'amount' => $fee['amount'],
                        ]);
                    }
                }
            }



            return redirect()->route('monthlyfee.index')->with('message', 'Monthly fee record added successfully.');


    }



    public function generateForCurrentMonth()
    {
        $currentMonth = Carbon::now()->format('Y-m');


        $studentsWithAdmission = User::with('admissions')->get();


foreach ($studentsWithAdmission as $user) {
    foreach ($user->admissions as $admission) {
       
            $user = $admission->user_id;

            $existingChallans = MonthlyFee::where('fee_month', $currentMonth)
    ->where(function($q) use ($user) {
        $q->where('student_id', $user)
          ->orWhere('user_id', $user);
    })
    ->exists();
  

            if ($existingChallans) {
                continue;
            }
            // Get last month's fee
            $lastMonth = Carbon::now()->subMonth()->format('Y-m');
          $lastFee = MonthlyFee::where(function($q) use ($user) {
        $q->where('student_id', $user)
          ->orWhere('user_id', $user);
    })
    ->where('fee_month', $lastMonth)
    ->first();


            $tuitionFee = 0;

            if ($lastFee) {
               
                $tuitionFeeDetail = MonthlyFeeDetail::where('monthly_fee_id', $lastFee->id)
                    ->where('description', 'like', '%tuition%')
                    ->first();
                $tuitionFee = $tuitionFeeDetail->amount ?? 0;
            }else{
                continue;
            }

            // Skip if no tuition fee
            if ($tuitionFee <= 0) {
                continue;
            }

            // Generate new receipt number
            $lastReceipt = DB::table('monthly_fees')->latest('id')->first();
            $lastNumber = $lastReceipt ? (int) str_replace('REC-', '', $lastReceipt->receipt_no) : 0;
            $receiptNumber = 'REC-' . str_pad($lastNumber + 1, 5, '0', STR_PAD_LEFT);
            $generatedBy = $lastFee ? $lastFee->generated_by : auth()->user()->name;
            
            // Create MonthlyFee
            $monthlyFee = MonthlyFee::create([
                'date' => Carbon::now()->toDateString(),
                'receipt_no' => $receiptNumber,
                'fee_month' => $currentMonth,
                'discount' => 0,
                'discounted_tuition_fee' => 0,
                'total_amount' => $tuitionFee,
                'student_id' => $admission->id,
                'generated_by' => $generatedBy,
            ]);

            // Create Fee Detail
            MonthlyFeeDetail::create([
                'monthly_fee_id' => $monthlyFee->id,
                'description' => 'Tuition Fee',
                'amount' => $tuitionFee,
            ]);
        }
    }
   
        return redirect()->route('monthlyfee.index')->with('message', 'Challans for the current month generated successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $monthlyFee = MonthlyFee::with('details', 'student')->findOrFail($id);
        return view('fees.challan', compact('monthlyFee'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $monthlyFee = MonthlyFee::with('details')->findOrFail($id);

        $students = Admission::all();
$users=User::where('usertype', 'user')->doesntHave('admissions')->doesntHave('monthlyFeesAsUser')->get();
        return view('fees.edit', compact('monthlyFee', 'students','users'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:admissions,id',
            'date' => 'required|date',
            'receiving_date' => 'nullable|date',
            'fee_month' => 'required',
            'discount' => 'nullable|numeric|min:0|max:100',
            'discounted_tuition_fee' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'amount_words' => 'nullable|string',
            'receiver_name' => 'nullable|string|max:255',
            'fees' => 'nullable|array',
            'fees.*.description' => 'nullable|string',
            'fees.*.amount' => 'nullable|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $monthlyFee = MonthlyFee::findOrFail($id);
            $monthlyFee->update([
                'student_id' => $request->student_id,
                'date' => $request->date,
                'receiving_date' => $request->receiving_date,
                'fee_month' => $request->fee_month,
                'discount' => $request->discount ?? 0,
                'discounted_tuition_fee' => $request->discounted_tuition_fee ?? 0,
                'total_amount' => $request->total_amount,
                'amount_words' => $request->amount_words ?? null,
                'receiver_name' => $request->receiver_name ?? null,
            ]);

            // Delete existing fee details before inserting updated ones
            $monthlyFee->details()->delete();

            // Insert updated fee breakdown
            foreach ($request->fees as $fee) {
                MonthlyFeeDetail::create([
                    'monthly_fee_id' => $monthlyFee->id,
                    'description' => $fee['description'],
                    'amount' => $fee['amount'],
                ]);
            }

            DB::commit();
            return redirect()->route('monthlyfee.index')->with('message', 'Monthly fee updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->broute->with('error', 'Failed to update record: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $monthlyfee=MonthlyFee::findOrFail($id);
        $monthlyfee->details()->delete();
        $monthlyfee->delete();
        return redirect()->route('monthlyfee.index')->with('message',"Record Deleted Successfully");
    }
    public function studentdetails($id) {
        $student = Admission::with('grade')->where('user_id', $id)->first();

        if ($student) {
            return response()->json([
                 'class' => $student->grade ? $student->grade->name : null,
                'father_name' => $student->father_name,
            ]);
        }

        return response()->json(['error' => 'Student not found'], 404);
    }
    public function pay($id)
    {

        $monthlyfee = MonthlyFee::findOrFail($id);
           if($monthlyfee->receiving_date==null){
               $monthlyfee->updatePaymentStatus();
               if($monthlyfee->status==='paid'){
                   $monthlyfee->update([

            'receiving_date' => Carbon::now(),
        ]);

        return redirect()->back()->with('message', 'Fee marked as paid successfully!');
    }

}elseif($monthlyfee->receiving_date!==null){
        $monthlyfee->update([

            'status' => 'paid',
        ]);
        return redirect()->back()->with('message', 'Fee marked as paid successfully!');
        }
        return redirect()->back()->with('message', 'Fee receipt not uploaded');
    }
    public function markAsPaid(Request $request)
{
    $request->validate([
        'monthly_fee_id' => 'required|exists:monthly_fees,id',
        'receiving_date' => 'required|date',
        'receiver_name' => 'required|string|max:255',
    ]);

    $fee = MonthlyFee::findOrFail($request->monthly_fee_id);
    $fee->status = 'paid';
    $fee->receiving_date = $request->receiving_date;
    $fee->receiver_name = $request->receiver_name;
    $fee->save();

    return redirect()->back()->with('message', 'Fee marked as paid.');
}




}
