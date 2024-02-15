<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agency extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'agencies';

    public static $searchable = [
        'name',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'contact_number_1',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function agencyUsers()
    {
        return $this->hasMany(User::class, 'agency_id', 'id');
    }

    public function agencyCampaigns()
    {
        return $this->hasMany(Campaign::class, 'agency_id', 'id');
    }

    public function PresalesUsers()
    {
        return $this->hasMany(User::class, 'presales_id', 'id');
    }
}
