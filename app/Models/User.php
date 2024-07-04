<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Scopes\ActiveScop;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;

#[ScopedBy([ActiveScop::class])]
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'dob'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed'
        ];
    }

    public function getDobAttribute($value)
    {
        return $this->formatDateToOrdinal($value);
    }

    protected function formatDateToOrdinal($date)
    {
        // Parse the date using Carbon
        $carbonDate = Carbon::parse($date);

        // Get the day, month, and year components
        $day = $carbonDate->day;
        $month = $carbonDate->format('F');
        $year = $carbonDate->year;

        // Append ordinal suffix to the day
        $ordinalDay = $this->getOrdinalSuffix($day);

        // Return the formatted date
        return "{$ordinalDay} {$month} {$year}";
    }


    protected function getOrdinalSuffix($day)
    {
        if (!in_array(($day % 100), [11, 12, 13])){
            switch ($day % 10) {
                case 1:
                    return $day . 'st';
                case 2:
                    return $day . 'nd';
                case 3:
                    return $day . 'rd';
            }
        }
        return $day . 'th';
    }

    protected function Name(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucfirst($value),
            set: fn (string $value) => strtolower($value),
        );
    }




    public function tasks()
    {
        return $this->hasMany(Task::class);
    }


}
