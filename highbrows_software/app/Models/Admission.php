<?php

namespace App\Models;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name', 'father_name', 'mother_name', 'student_cnic', 'father_cnic',
        'mother_cnic', 'guardian_name', 'religion', 'pre_class',
        'grade_applied_for', 'dob', 'res_type', 'contact', 'guardian_phone',
        'guardian_whatsapp', 'domicile', 'postal_address', 'father_income',
        'passport_pic', 'b_form', 'father_cnic_doc', 'result_card', 'apply_cadet_colleges','guardian_contact','admission_date','custom_id',"user_id",
    ];
public static function boot()
{
    parent::boot();

    static::creating(function ($admission) {
        $year = date('Y', strtotime($admission->admission_date));
        $shortYear = substr($year, -2); // e.g., '25' for 2025
        $baseId = (int) $shortYear * 1000; // e.g., 25000

        // Determine suffix based on user category
        $category = $admission->res_type;
        $suffix = match ($category) {
            'Online'     => '-O',
            'DayScholar' => '-D',
            'Hostel'     => '-H',
            default      => '',
        };

        $nextNumber = $baseId + 1;

        // Keep generating until a unique custom_id is found
        do {
            $customId = $nextNumber . $suffix;
            $exists = self::where('custom_id', $customId)->exists();
            $nextNumber++;
        } while ($exists);

        $admission->custom_id = $customId;
    });
}


    /**
     * Relationship with Class model (Grade applied for).
     */
    public function grade()
    {
        return $this->belongsTo(Clase::class, 'grade_applied_for');
    }
    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id'); // Assuming 'exam_id' is the foreign key
    }
    /**
     * Relationship with CadetCollege model (Many-to-many relationship through pivot table).
     */
    public function cadetColleges()
    {
        return $this->belongsToMany(College::class, 'admission_cadet_colleges', 'admission_id', 'college_id');
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
