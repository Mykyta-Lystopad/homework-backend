<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Image;
use Storage;
use Str;

class Attachment extends Model
{
    use SoftDeletes;

    public const FILE_PATH_FORMAT = 'attachments/attachment_%1$s/';

    /**
     * @var array
     */
    protected $fillable = [
        'file_name',
        'comment'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            if (auth()->check()) {
                $model->user_id = auth()->id();
            }
        });

        static::deleting(function (self $model) {
            $model->deleteFile();
        });
    }

    /**
     * @return string
     */
    public function getFileLinkAttribute(): string
    {
        return Storage::url(sprintf(self::FILE_PATH_FORMAT, $this->getKey()) . $this->file_name . '?t=' . $this->updated_at->timestamp);
    }

    /**
     * @return string
     */
    public function getThumbLinkAttribute(): string
    {
        return Storage::url(sprintf(self::FILE_PATH_FORMAT, $this->getKey()) . 'thumb_' . $this->file_name . '?t=' . $this->updated_at->timestamp);
    }

    /**
     * @param $value
     */
    public function setFileNameAttribute($value): void
    {
        setlocale(LC_ALL, 'en_US.UTF-8');
        $file_name_parts = pathinfo($value);
        $this->attributes['file_name'] = Str::slug($file_name_parts['filename']) . '.' . $file_name_parts['extension'];
        setlocale(LC_ALL, null);
    }


    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasOne
     */
    public function avatarUser(): HasOne
    {
        return $this->hasOne(User::class, 'avatar_id');
    }

    /**
     * @return MorphToMany
     */
    public function assignments(): MorphToMany
    {
        return $this->morphedByMany(Assignment::class, 'attachmentable')->withTimestamps();
    }

    /**
     * @return MorphToMany
     */
    public function answers(): MorphToMany
    {
        return $this->morphedByMany(Answer::class, 'attachmentable')->withTimestamps();
    }

    /**
     * @param string $content
     */
    public function saveFile(string $content): void
    {
        $dir_path = sprintf(self::FILE_PATH_FORMAT, $this->getKey());
        Storage::makeDirectory($dir_path);
        $img = Image::make($content)->resize(1280, 1280, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->save(Storage::path($dir_path . $this->file_name));
        $img->fit(200, 300)->save(Storage::path($dir_path . 'thumb_' . $this->file_name));
    }

    /**
     * Delete file from storage
     */
    public function deleteFile(): void
    {
        $file_path = sprintf(self::FILE_PATH_FORMAT, $this->getKey());
        Storage::deleteDirectory($file_path);
    }
}
