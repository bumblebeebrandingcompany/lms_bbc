<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Walkin extends Model
{
    use HasFactory;
    protected $appends = ['is_superadmin', 'is_client', 'is_agency', 'is_admissionteam', 'is_frontoffice',];

    public $table = 'walkinform';

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
        'additional_email',
        'source_id',
        'project_id',
        'campaign_id',
    ];


    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function sources()
    {
        return $this->belongsTo(Source::class, 'source_id');
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
