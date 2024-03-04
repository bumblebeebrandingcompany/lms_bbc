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
        'father_name',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'father_name',
        'email',
        'phone',
        'secondary_phone',
        'secondary_email',
        'sub_source_id'
    ];
    public function leads()
    {
        return $this->hasMany(Lead::class,'walkin_id');
    }

    public function subsource()
    {
        return $this->belongsTo(SubSource::class, 'sub_source_id');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id');
    }
    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
