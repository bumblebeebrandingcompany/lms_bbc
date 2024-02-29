<?php

namespace App\Models;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StageNotes extends Model
{
    use HasFactory;

    public $table = 'stage_notes';



    protected $dates = [
        'created_at',
        'updated_at',

    ];

    protected $fillable = [
        'stage',
        'notes',
        'lead_id',
        'stage_id',
        'created_at',
        'updated_at',
    ];


}
