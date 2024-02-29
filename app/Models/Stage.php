<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    use HasFactory;

    protected $fillable = ['stage_id', 'selected_child_stages'];

    public function parentStage()
    {
        return $this->belongsTo(ParentStage::class);
    }
}
