<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Teacher extends Model
{
    use HasFactory;
    public function classes()
    {
        return $this->hasMany(Clase::class, 'teacher_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function salaries()
    {
        return $this->hasMany(Salarie::class, 'teacher_id')->where('status', 'paid');
    }
    public function attendances()
{
    return $this->hasMany(EmployeeAttendance::class, 'teacher_id');
}

    
  public static function getStatusSummaryByUserId($userId)
{
    $teacher = self::where('user_id', $userId)->first();

    if (!$teacher) {
        return null;
    }

    // Get current month
    $month = Carbon::now()->format('Y-m');

    // Salary (assumes latest paid status of current month if available)
    $salaryStatus = $teacher->salaries()
        ->where('date', 'like', "$month%")
        ->latest()
        ->value('status') ?? 'unpaid';

    // Attendance (status of today, or count of present days in current month)
    $today = Carbon::today()->toDateString();
    $todayStatus = $teacher->attendances()->where('date', $today)->value('status');

    return [
        'salary' => $teacher->salary,
        'salary_status' => $salaryStatus,
        'attendance_today' => $todayStatus ?? 'Not marked yet',
    ];
}

}
