<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Auth\Events\Validated;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Admission;
use App\Models\Document;
use Validator;
class AdmissionController extends Controller
{
    public function fetchAll()
{
    try {
        // Fetch all students with their admission and document details
        $admission_record = Student::with(['admission', 'documents'])->get();

        if ($admission_record->isNotEmpty()) { // Check if the collection is not empty
            return response()->json([
                'status' => 200,
                'message' => $admission_record
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'No record found'
            ], 404);
        }
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'An error occurred while fetching the data',
            'message' => $e->getMessage()
        ], 500);
    }
}
// yaarar yajo hyna get single yae use hota ha edit form k leya alg sy edit ke api bnany ke zrurt nii ha uae get krlyga
public function getSingleUser($userid){
    try{
        $singleUserRecord=Student::with(['admission','documents'])->find($userid);
        if(!$singleUserRecord){
    return response()->json([
'status'=>404,
'message'=>'Record Not Found'
    ]);
        }else{
            return response()->json([
'status'=>200,
'message'=>$singleUserRecord
            ]);
        }
    }catch(\Exception $e){
        return response()->json([
            "message"=>"Error Occured While Fetching Users Record",
            "error"=>$e->getMessage()
        ]);
    }
 
}
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
    
            // Validate the request data
            $validator = Validator::make($request->all(), [
                // New fields
                'full_name' => 'required|string|max:255',
                'father_name' => 'required|string|max:255',
                'mother_name' => 'required|string|max:255', 
                'father_cnic' => 'required|string|max:20', 
                'mother_cnic' => 'required|string|max:20',
                'student_cnic' => 'required|string|max:20', 
                'guardian_name' => 'nullable|string|max:255',
                'religion' => 'required|string|max:100',
                'sect' => 'nullable|string|max:100',
                'dob' => 'required|date',
                'gender' => 'required|string|in:Male,Female,Other',
                'contact_number' => 'required|string|max:15',
                'domicile_district' => 'required|string|max:255',
                'postal_address' => 'required|string|max:500',
                'guardian_contact' => 'required|array',
                'guardian_contact.phone' => 'required|string|max:20',
                'guardian_contact.whatsapp' => 'nullable|string|max:20',
                'age_on_april_2025' => 'required|array',
                'age_on_april_2025.years' => 'nullable|integer|min:0|max:100',
                'age_on_april_2025.months' => 'nullable|integer|min:0|max:12',
                'age_on_april_2025.days' => 'nullable|integer|min:0|max:31',
    
                // Documents
                'documents' => 'required|array|min:1',
                'documents.*.file' => 'required|file|mimes:pdf,jpg,png|max:2048', // Accepts PDF, JPG, PNG up to 2MB
                'documents.*.file_type' => 'required|string|max:50',
                'documents.*.file_name' => 'required|string|max:255',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Validation errors occurred.',
                    'errors' => $validator->errors(),
                ], 422);
            }
    
            // Proceed with the validated data
            $validated = $validator->validated();
    
            // Save student details
            $student = Student::create([
                'full_name' => $validated['full_name'],
                'father_name' => $validated['father_name'],
                'mother_name' => $validated['mother_name'],
                'father_cnic' => $validated['father_cnic'],
                'mother_cnic' => $validated['mother_cnic'],
                'student_cnic' => $validated['student_cnic'],
                'guardian_name' => $validated['guardian_name'] ?? null,
                'religion' => $validated['religion'],
                'sect' => $validated['sect'] ?? null,
                'dob' => $validated['dob'],
                'gender' => $validated['gender'],
                'contact_number' => $validated['contact_number'],
                'domicile_district' => $validated['domicile_district'],
            ]);
    
            // Save admission details
            $admission = Admission::create([
                'student_id' => $student->id,
                'postal_address' => $validated['postal_address'],
                'guardian_phone' => $validated['guardian_contact']['phone'],
                'guardian_whatsapp' => $validated['guardian_contact']['whatsapp'] ?? null,
                'age_on_april_2025_years' => $validated['age_on_april_2025']['years'] ?? null,
                'age_on_april_2025_months' => $validated['age_on_april_2025']['months'] ?? null,
                'age_on_april_2025_days' => $validated['age_on_april_2025']['days'] ?? null,
                'status' => 'Pending',
                'admission_date' => now(),
            ]);
    
            // Initialize documents array
            $documents = [];
    
            // Check if the request contains files
            if ($request->has('documents')) {
                foreach ($request->input('documents') as $index => $document) {
                    if ($request->hasFile("documents.{$index}.file")) {
                        $file = $request->file("documents.{$index}.file");
    
                        // Store the file in the 'uploads/documents' directory in the public storage
                        $filePath = $file->store('uploads/documents', 'public');
    
                        // Create a record in the 'Document' table
                        $documents[] = Document::create([
                            'student_id' => $student->id, // Associate document with student
                            'file_path' => $filePath, // Store the file path in the database
                            'file_type' => $document['file_type'], // File type from the request
                            'file_name' => $document['file_name'], // File name from the request
                        ]);
                    }
                }
            }
    
            DB::commit();
    
            return response()->json([
                'message' => 'Student, admission, and documents created successfully.',
                'student' => $student,
                'admission' => $admission,
                'documents' => $documents
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'An error occurred while saving the data.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
// agr form sy dat deti hun to ye sari field e emplty show krwata h lakin jb raw mai deti hun tb sirf file ka e issue ata h
// yaar ya notfound jo hyna isy route nii mil raha ya logic k error nii ha route k ha dekho q nii mil raha ok
public function updateForm(Request $request,$id)
{
    dd($request->all());
}
public function update(Request $request, $userid){
    try {
        DB::beginTransaction();
                dd($request->all());
        // Validate the request data
        $validator = Validator::make($request->all(), [
            // New fields
            'full_name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'father_cnic' => 'required|string|max:20',
            'mother_cnic' => 'required|string|max:20',
            'student_cnic' => 'required|string|max:20',
            'guardian_name' => 'nullable|string|max:255',
            'religion' => 'required|string|max:100',
            'sect' => 'nullable|string|max:100',
            'dob' => 'required|date',
            'gender' => 'required|string|in:Male,Female,Other',
            'contact_number' => 'required|string|max:15',
            'domicile_district' => 'required|string|max:255',
            'postal_address' => 'required|string|max:500',
            'guardian_contact' => 'required|array',
            'guardian_contact.phone' => 'required|string|max:20',
            'guardian_contact.whatsapp' => 'nullable|string|max:20',
            'age_on_april_2025' => 'required|array',
            'age_on_april_2025.years' => 'nullable|integer|min:0|max:100',
            'age_on_april_2025.months' => 'nullable|integer|min:0|max:12',
            'age_on_april_2025.days' => 'nullable|integer|min:0|max:31',

            // Documents
            'documents' => 'nullable|array|min:1',
            'documents.*.file' => 'nullable|file|mimes:pdf,jpg,png|max:2048',
            'documents.*.file_type' => 'nullable|string|max:50',
            'documents.*.file_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            \Log::error('Validation Errors:', $validator->errors()->toArray());
            return response()->json([
                'message' => 'Validation errors occurred.',
                'errors' => $validator->errors(),
            ], 422);
        }
        

        // Find the existing student record
        $student = Student::find($userid);

        if (!$student) {
            return response()->json([
                'status' => 404,
                'message' => 'Student not found'
            ], 404);
        }

        // Update student details
        $student->update([
            'full_name' => $request->full_name,
            'father_name' => $request->father_name,
            'mother_name' => $request->mother_name,
            'father_cnic' => $request->father_cnic,
            'mother_cnic' => $request->mother_cnic,
            'student_cnic' => $request->student_cnic,
            'guardian_name' => $request->guardian_name ?? null,
            'religion' => $request->religion,
            'sect' => $request->sect ?? null,
            'dob' => $request->dob,
            'gender' => $request->gender,
            'contact_number' => $request->contact_number,
            'domicile_district' => $request->domicile_district,
        ]);

        // Update admission details
        $admission = $student->admission;
        if ($admission) {
            $admission->update([
                'postal_address' => $request->postal_address,
                'guardian_phone' => $request->guardian_contact['phone'],
                'guardian_whatsapp' => $request->guardian_contact['whatsapp'] ?? null,
                'age_on_april_2025_years' => $request->age_on_april_2025['years'] ?? null,
                'age_on_april_2025_months' => $request->age_on_april_2025['months'] ?? null,
                'age_on_april_2025_days' => $request->age_on_april_2025['days'] ?? null,
            ]);
        }

        // Update documents
        $documents = $student->documents; // Get the student's current documents
        if ($request->has('documents')) {
            foreach ($request->input('documents') as $index => $document) {
                if ($request->hasFile("documents.{$index}.file")) {
                    $file = $request->file("documents.{$index}.file");

                    // Store the new file in the 'uploads/documents' directory in the public storage
                    $filePath = $file->store('uploads/documents', 'public');

                    // If the document already exists, delete the old file (optional)
                    if (isset($documents[$index])) {
                        // Delete the old document's file from the storage
                        Storage::disk('public')->delete($documents[$index]->file_path);
                        // Update the existing document record
                        $documents[$index]->update([
                            'file_path' => $filePath,
                            'file_type' => $document['file_type'],
                            'file_name' => $document['file_name'],
                        ]);
                    } else {
                        // Create a new document record if it's a new document
                        Document::create([
                            'student_id' => $student->id,
                            'file_path' => $filePath,
                            'file_type' => $document['file_type'],
                            'file_name' => $document['file_name'],
                        ]);
                    }
                }
            }
        }

        DB::commit();

        return response()->json([
            'status' => 200,
            'message' => 'Student, admission, and documents updated successfully.',
            'student' => $student,
            'admission' => $admission,
            'documents' => $documents
        ], 200);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'error' => 'An error occurred while updating the data.',
            'message' => $e->getMessage(),
        ], 500);
    }
}
public function destroy($student_id)
{
    try {
        DB::beginTransaction();

        // Find the student by ID
        $student = Student::find($student_id);

        if (!$student) {
            return response()->json([
                'error' => 'Student not found.',
            ], 404);
        }

        // Find the related admission record and delete it
        $admission = Admission::where('student_id', $student_id)->first();
        if ($admission) {
            $admission->delete();
        }

        // Find and delete related documents
        $documents = Document::where('student_id', $student_id)->get();
        foreach ($documents as $document) {
            // Delete the physical file if it exists in storage
            if (Storage::exists($document->file_path)) {
                Storage::delete($document->file_path);
            }
            // Delete the document record from the database
            $document->delete();
        }

        // Finally, delete the student record
        $student->delete();

        DB::commit();

        return response()->json([
            'message' => 'Student, admission, and documents deleted successfully.',
        ], 200);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'error' => 'An error occurred while deleting the data.',
            'message' => $e->getMessage(),
        ], 500);
    }
}

}
