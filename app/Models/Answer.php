<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Answer extends Model
{
    protected $fillable = [
        'assignment_id',
        'user_id'
    ];

    /**
     * @return BelongsTo
     */
    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }


    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return MorphToMany
     */
    public function attachments(): MorphToMany
    {
        return $this->morphToMany(Attachment::class, 'attachmentable')->withTimestamps();
    }

    /**
     * @return MorphMany
     */
    public function messages(): MorphMany
    {
        return $this->morphMany(Message::class, 'messageable');
    }

    /**
     * @return HasMany
     */
    public function solutions(): HasMany
    {
        return $this->hasMany(Solution::class);
    }
}
