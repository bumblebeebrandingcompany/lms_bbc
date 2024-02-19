<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Walkin extends Model
{
    use  HasFactory;
    protected $appends = ['is_superadmin', 'is_client', 'is_agency','is_admissionteam','is_frontoffice', ];

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
    public function rules()
    {
        $project_id = request()->input('project_id');
        return [
            'name' => 'required',
            'email' => [
                auth()->user()->is_superadmin ? '' : 'required',
                auth()->user()->is_superadmin ? '' : 'email',
                Rule::unique('leads')->where(function ($query) use ($project_id) {
                    return $query->whereNotNull('email')->where('project_id', $project_id);
                }),
            ],
            'phone' => [
                auth()->user()->is_superadmin ? '' : 'required',
                Rule::unique('leads')->where(function ($query) use ($project_id) {
                    return $query->whereNotNull('phone')->where('project_id', $project_id);
                }),
            ],
        ];

    }

    public function leads()
{
    return $this->belongsTo(Lead::class,'walkin_id');
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
