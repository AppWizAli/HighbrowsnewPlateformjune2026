<?php

namespace App\Http\Controllers;
use App\Models\Fee;
use App\Models\User;
use App\Models\Installment;
use Auth;
use Illuminate\Http\Request;
use App\Models\Admission;
use App\Services\PdfService;
use DB;
class FeesController extends Controller
{
    protected $pdfService;

    public function __construct(PdfService $pdfService)
    {
        $this->pdfService = $pdfService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fees = Fee::with('installment')->get();

        return view('admin.student-fees', compact('fees'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $students=User::where('usertype','user')
                ->doesntHave('admissions')->get();
        return view('admin.add-fees', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|unique:fees,student_id',
            'total_fee' => 'required|numeric',
            'advance' => 'nullable|numeric',
            'installments' => 'nullable|array',
            'installments.*.amount' => 'nullable|numeric',
            'installments.*.due_date' => 'nullable|date',
        ]);

        DB::beginTransaction();

        try {
            // Create the fee record with the installments count
            $fee = Fee::create([
                'student_id' => $validated['student_id'],
                'total_fee' => $validated['total_fee'],
                'advance' => $validated['advance'] ?? 0,
                'installments' => isset($validated['installments']) ? count($validated['installments']) : 0,
                'due_date' => $validated['installments'][0]['due_date'] ?? now(),

            ]);

            // Process installments if they exist
            if (!empty($validated['installments'])) {
                foreach ($validated['installments'] as $installment) {
                    // Ensure both amount and due_date are provided
                    if (isset($installment['amount']) && isset($installment['due_date'])) {
                        Installment::create([
                            'fee_id' => $fee->id,
                            'amount' => $installment['amount'],
                            'due_date' => $installment['due_date'],
                            'paid' => false,  // Initially set as unpaid
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('fees.index')->with('success', 'Fee details added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Fee creation failed: ' . $e->getMessage()); // Log the error message
            return redirect()->route('fees.index')->with('error', 'There was an error processing your request. Please try again.');
        }
    }

    public function feeDetails()
    {

        $userId = Auth::user()->id;
        // $student = Admission::where('user_id', $userId)->first();
        // if (!$student) {
        //     return redirect()->back()->with('error', 'Student record not found.');
        // }
        $fees = Fee::with('installment')->where('student_id', $userId)->get();

        return view('student.fee-record', compact('fees'));
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
{
    $installment = Installment::findOrFail($id);

    $installment->fee->updatePaymentStatus(); // Assuming this method exists and does what's needed

    return response()->json([
        'success' => true,
        'status' => $installment->fee->status, // Optional: return status
        'message' => 'Status updated successfully.'
    ]);
}

    public function uploadReceipt(Request $request, $installmentId)
    {
        $request->validate([
            'receipt' => 'required|mimes:jpg,png,pdf|max:2048',
        ]);

        $installment = Installment::findOrFail($installmentId);

        $fileName = time().'_'.$request->file('receipt')->getClientOriginalName();
        $request->file('receipt')->storeAs('public/receipts', $fileName);


        $installment->receipt = $fileName;
        $installment->save();


        // $installment->fee->checkIfFullyPaid();

        return back()->with('success', 'Receipt uploaded successfully.');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try{
            $fees=Fee::findOrFail($id);
            $fees->installment()->delete();
            $fees->delete();
            DB::commit();
            return redirect()->route('fees.index')->with('message','Fee Record Deleted Successfully');
        }catch(\Exception $e){
            DB::rollBack();
            return redirect()->route('fees.index')->with('message','Fee Record Deleted Successfully');
        }


    }


    public function generateChallan($installmentId)
    {

        $installment = Installment::with('fee.student')->findOrFail($installmentId);

        return view('admin.challan', compact('installment'))->render();


        // $pdf = $this->pdfService->generatePdf($html);

        // Stream the PDF to the browser
        // return $pdf->stream('challan-' . $installment->id . '.pdf');
    }

    public function generateReceipt($feeId)
    {
        // Fetch the fee with the related student data
        $fee = Fee::with('student')->findOrFail($feeId);


       return view('admin.receipt', compact('fee'))->render();


//         $pdf = $this->pdfService->generatePdf($html);

//  return response($pdf->output())
//  ->header('Content-Type', 'application/pdf')
//  ->header('Content-Disposition', 'inline; filename="receipt-' . $fee->id . '.pdf"');

    }


}
