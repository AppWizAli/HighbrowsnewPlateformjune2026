<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class StudentAttendance extends Model
{
    use HasFactory;
    protected $fillable=['student_id','class_id','status','date',];
    public function student(){
        return $this->belongsTo(Admission::class,'studnet_id');

    }
    public function class(){
        return $this->belongsTo(Clase::class,'class_id');
    }


    

    public static function calculateMonthlyAttendance($studentId)
    {
        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $today = Carbon::now()->toDateString();

        // Get attendance grouped by status
        $rawStats = self::where('student_id', $studentId)
            ->whereBetween('date', [$startOfMonth, $today])
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $present = $rawStats['present'] ?? 0;
        $absent = $rawStats['absent'] ?? 0;
        $leave = $rawStats['leave'] ?? 0;

        return [
            'total'   => $present + $absent + $leave,
            'present' => $present,
            'absent'  => $absent,
            'leave'   => $leave,
        ];
    }


}
