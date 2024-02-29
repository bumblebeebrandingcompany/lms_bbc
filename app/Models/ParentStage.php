<?php

// app/Models/ParentStage.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentStage extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'tag_id'];

    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    public function childStages()
    {
        // Adjust the relationship based on your actual column names
        return $this->hasMany(Stage::class, 'stage_id', 'id');
    }

    public function getChildStagesByTag($tagId)
    {
        return $this->childStages->where('tag_id', $tagId);
    }


}
