<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assignment extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'due_date',
        'group_id'
    ];

    protected $casts = [
        'due_date' => 'date'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function (self $model) {
            if (auth()->check()) {
                $model->user_id = auth()->id();
            }
        });
    }

    /**
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'answers')->withTimestamps();
    }

    /**
     * @return HasMany
     */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }


    /**
     * @return HasOne
     */
    public function userAnswer(): HasOne
    {
        return $this->hasOne(Answer::class)
            ->where('user_id', request()->user_id ?? auth()->id());
    }

    /**
     * @return HasMany
     */
    public function problems(): HasMany
    {
        return $this->hasMany(Problem::class);
    }

    /**
     * @return MorphToMany
     */
    public function attachments(): MorphToMany
    {
        return $this->morphToMany(Attachment::class, 'attachmentable')->withTimestamps();
    }
}
