<?php

namespace App\Models;

use App\Traits\Auditable;
use Carbon\Carbon;
use DateTimeInterface;
use Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use SoftDeletes, Notifiable, Auditable, HasFactory;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['is_superadmin', 'is_client', 'is_agency'];
    
    public $table = 'users';

    protected $hidden = [
        'remember_token',
        'password',
    ];

    public static $searchable = [
        'name',
        'contact_number_1',
    ];

    protected $dates = [
        'email_verified_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const USER_TYPE_RADIO = [
        'Superadmin' => 'Superadmin',
        'Client'     => 'Client',
        'Agency'     => 'Agency',
    ];

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'user_type',
        'address',
        'contact_number_1',
        'contact_number_2',
        'website',
        'client_id',
        'agency_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('id', 1)->exists();
    }

    public function createdByProjects()
    {
        return $this->hasMany(Project::class, 'created_by_id', 'id');
    }

    public function clientProjects()
    {
        return $this->hasMany(Project::class, 'client_id', 'id');
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class, 'agency_id');
    }

    /**
     * is user super admin?
     *
     * @return boolean
     */
    public function getIsSuperadminAttribute()
    {
        return $this->user_type == 'Superadmin';
    }

    /**
     * is user client?
     *
     * @return boolean
     */
    public function getIsClientAttribute()
    {
        return $this->user_type == 'Client';
    }

    /**
     * is user agency?
     *
     * @return boolean
     */
    public function getIsAgencyAttribute()
    {
        return $this->user_type == 'Agency';
    }
}
