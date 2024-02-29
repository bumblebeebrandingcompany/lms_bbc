<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Walkin extends Model
{
    use HasFactory;
    protected $appends = ['is_superadmin', 'is_client', 'is_agency', 'is_admissionteam', 'is_frontoffice',];

    public $table = 'walkins';

    public static $searchable = [
        'name',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'phone',
        'secondary_phone',
        'secondary_email',
        'subsource_id'
    ];


    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function subsource()
    {
        return $this->belongsTo(SubSource::class, 'sub_source_id');
    }

}
