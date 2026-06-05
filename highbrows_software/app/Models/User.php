<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'contact',
        'email',
        'grade',
        'category',
        'password',
        'usertype',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];
    public function admissions()
    {
        return $this->hasMany(Admission::class, 'user_id');
    }
    public function teachers()
    {
        return $this->hasMany(Teacher::class, 'user_id');
    }
   // Monthly fees directly linked to user (via user_id)
public function monthlyFeesAsUser() {
    return $this->hasMany(MonthlyFee::class, 'user_id');
}

// Monthly fees linked via student_id (if user ID is stored there)
public function monthlyFeesAsStudent() {
    return $this->hasMany(MonthlyFee::class, 'student_id');
}

public function monthlyfee()
{
    return $this->monthlyFeesAsUser();
}

}
