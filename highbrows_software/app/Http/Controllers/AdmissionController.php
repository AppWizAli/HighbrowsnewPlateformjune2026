<?php

namespace App\Http\Controllers;
use App\Models\Admission;
use App\Models\Clase;
use App\Models\College;
use App\Models\AdmissionCadetCollege;;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Hash;
use App\Models\User;
use Auth;
use Validator;
class AdmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function __construct()
    {
       
    }
     
    public function index()
    {
        // dd(auth()->check(), auth()->user());
 if (!auth()->check()) {
            return redirect()->route('login.form')->send();
        }
        $admissions = Admission::all(); // Fetch all admissions data
        return view('admin.students', compact('admissions'));
    }
public function showRegistrationForm(){
    return view('admin.register-student');
}
public function updateUser(Request $request, $id)
{
    $user = User::findOrFail($id);


    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'contact' => 'required|string|max:20',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'password' => 'nullable|string|min:8|confirmed',
    ]);


    if ($request->filled('password')) {
        $validatedData['password'] = bcrypt($request->password);
    } else {

        unset($validatedData['password']);
    }


    $user->update($validatedData);

    return redirect()->back()->with('message', 'Profile updated successfully.');
}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $classes=Clase::all();
        return view('admissions',compact('classes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function store(Request $request)
     {
             if(Auth::check()){
         $validated = Validator::make($request->all(), [
             'user_id' => 'required|exists:users,id',
             'full_name' => 'required|string|max:255',
             'father_name' => 'required|string|max:255',
             'mother_name' => 'required|string|max:255',
             'student_cnic' => 'required|string|max:15|unique:admissions,student_cnic',
             'mother_cnic' => 'required|string|max:15',
             'father_cnic' => 'required|string|max:15',
             'guardian_name' => 'nullable|string|max:255',
             'religion' => 'required|string|max:100',
             'pre_class' => 'required|string|max:100',
             'grade_applied_for' => 'required|exists:classes,id',
             'dob' => 'required|date',
             'admission_date' => 'required|date',
             'res_type' => 'required|in:Hostel,DayScholar,Online',
             'contact' => 'required|string|max:15',
             'guardian_phone' => 'nullable|string|max:15',
             'guardian_whatsapp' => 'nullable|string|max:15',
             'guardian_contact' => 'nullable|string|max:15',
             'domicile' => 'required|string|max:255',
             'postal_address' => 'required|string',
             'father_income' => 'required|numeric|min:0',
             'passport_pic' => 'required|image|mimes:jpeg,png,jpg,gif',
             'b_form' => 'required|file|mimes:pdf,jpg,png',
             'father_cnic_doc' => 'nullable|file|mimes:pdf,jpg,png',
             'result_card' => 'nullable|file|mimes:pdf,jpg,png',
             'apply_cadet_colleges' => 'nullable|boolean',
             'cadet_colleges' => 'nullable|array',
             'cadet_colleges.*' => 'exists:colleges,id',
         ]);

         if ($validated->fails()) {
             return back()->withErrors($validated)->withInput();
         }


         $data = $validated->validated();
}else{
    $data=$request->all();
}
         DB::beginTransaction();

         try {

             $files = [];

             if ($request->hasFile('passport_pic')) {
                 $file = $request->file('passport_pic');
                 $folderName = 'passportPics';
                 $fileName = time() . '-' . $file->getClientOriginalName();
                 $filePath = $file->storeAs('public/' . $folderName, $fileName);
                 $files['passport_pic'] = $folderName . '/' . $fileName;
             }

             if ($request->hasFile('father_cnic_doc')) {
                 $file = $request->file('father_cnic_doc');
                 $folderName = 'fatherCnicDocs';
                 $fileName = time() . '-' . $file->getClientOriginalName();
                 $filePath = $file->storeAs('public/' . $folderName, $fileName);
                 $files['father_cnic_doc'] = $folderName . '/' . $fileName;
             }

             if ($request->hasFile('result_card')) {
                 $file = $request->file('result_card');
                 $folderName = 'resultCards';
                 $fileName = time() . '-' . $file->getClientOriginalName();
                 $filePath = $file->storeAs('public/' . $folderName, $fileName);
                 $files['result_card'] = $folderName . '/' . $fileName;
             }

             $admission = Admission::create([
                 'user_id' => $data['user_id'],
                 'full_name' => $data['full_name'],
                 'father_name' => $data['father_name'],
                 'mother_name' => $data['mother_name'],
                 'student_cnic' => $data['student_cnic'],
                 'mother_cnic' => $data['mother_cnic'],
                 'father_cnic' => $data['father_cnic'],
                 'guardian_name' => $data['guardian_name'] ?? null,
                 'religion' => $data['religion'],
                 'pre_class' => $data['pre_class'],
                 'grade_applied_for' => $data['grade_applied_for'],
                 'dob' => $data['dob'],
                 'admission_date' => $data['admission_date'],
                 'res_type' => $data['res_type'],
                 'contact' => $data['contact'],
                 'guardian_phone' => $data['guardian_phone'],
                 'guardian_whatsapp' => $data['guardian_whatsapp'] ?? null,
                 'guardian_contact' => $data['guardian_contact'] ?? null,
                 'domicile' => $data['domicile'],
                 'postal_address' => $data['postal_address'],
                 'father_income' => $data['father_income'],
                 'passport_pic' => $files['passport_pic'] ?? null,
                 'b_form' => $files['b_form'] ?? null,
                 'father_cnic_doc' => $files['father_cnic_doc'] ?? null,
                 'result_card' => $files['result_card'] ?? null,
                 'apply_cadet_colleges' => $data['apply_cadet_colleges'] ?? false,
             ]);

             if ($request->has('cadet_colleges') && count($data['cadet_colleges']) > 0) {
                 foreach ($data['cadet_colleges'] as $collegeId) {

                     DB::table('admission_cadet_colleges')->insert([
                         'admission_id' => $admission->id,
                         'college_id' => $collegeId,
                     ]);
                 }
             }


             DB::commit();
             $usertype = auth()->user()->usertype;

             if ($usertype == "user") {
                 return view('subadmin.dashboard');
             } elseif ($usertype == "admin") {
             return redirect()->route('admissions.index')->with('success', 'Admission successfully created.');
        }
     } catch (\Exception $e) {

             DB::rollBack();


             \Log::error('Error creating admission: ' . $e->getMessage());
             return redirect()->back()->withErrors(['error' => 'An error occurred, please try again. ' . $e->getMessage()]);
         }
     }










    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Fetch student details along with applied cadet colleges
        $student = Admission::with(['grade', 'cadetColleges'])->findOrFail($id);

        return view('admin.student-detail', compact('student'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $admission = Admission::with('cadetColleges')->findOrFail($id);

        $classes=Clase::all();
        $colleges=College::all();
        return view('admin.edit-student', compact('admission', 'classes', 'colleges'));

    }
    public function editUser($id)
    {
            $user = User::findOrFail($id);
        return view('student.edit-profile', compact('user'));
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

        $validated = $request->all();


        $admission = Admission::findOrFail($id);


        $admission->update($validated);


        $fileFields = ['passport_pic', 'b_form', 'father_cnic_doc', 'result_card'];

      foreach ($fileFields as $field) {
    if ($request->hasFile($field)) {
        // Delete the old file if it exists
        if (!empty($admission->$field) && Storage::disk('public')->exists($admission->$field)) {
            Storage::disk('public')->delete($admission->$field);
        }

        // Store the new file and update the model
        $filePath = $request->file($field)->store('admissions', 'public');
        $admission->$field = $filePath;
    }
}


        $admission->save();

        return redirect()->route('admissions.index')->with('success', 'Admission updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $admission = Admission::findOrFail($id);

        $user = User::where('id', $admission->user_id)->first();
        if ($user) {
            $user->delete();
        }

        if ($admission->passport_pic) {
            Storage::delete('public/' . $admission->passport_pic);
        }
        if ($admission->b_form) {
            Storage::delete('public/' . $admission->b_form);
        }
        if ($admission->father_cnic_doc) {
            Storage::delete('public/' . $admission->father_cnic_doc);
        }
        if ($admission->result_card) {
            Storage::delete('public/' . $admission->result_card);
        }


        $admission->delete();


        return redirect()->route('admissions.index')->with('success', 'Admission and associated user record deleted successfully.');
    }
    public function print($id){
                // Fetch student details along with applied cadet colleges
                $student = Admission::with(['grade', 'cadetColleges'])->findOrFail($id);

                return view('admin.print-admission', compact('student'));
    }
}
