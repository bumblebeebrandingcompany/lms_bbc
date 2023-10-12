<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeadEvents extends Model
{
    use HasFactory;

    public $table = 'lead_events';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'webhook_data' => 'array',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }
}
