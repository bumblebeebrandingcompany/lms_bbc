<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Document extends Model implements HasMedia
{
    use HasFactory;
    use HasUuids;
    use InteractsWithMedia;

    public $table = 'documents';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['files_url'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'files' => 'array',
    ];
    
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    /**
     * Get the files's url
     */
    protected function filesUrl(): Attribute
    {
        $files = [];
        if(!empty($this->files)) {
            foreach ($this->files as $file) {
                if(!empty($file)) {
                    $file_names_arr = explode('_', $file, 2);
                    $file_name = $file_names_arr[1] ?? $file_names_arr[0];
                    $files[] = [
                        'url' => url('storage/'.config('constants.document_files_path').'/'.$file),
                        'file_name' => $file_name,
                        'org_file_name' => $file
                    ];
                }
            }
        }
        return new Attribute(
            get: fn () => $files,
        );
    }
}
