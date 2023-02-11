<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Solution extends Model
{
    use SoftDeletes;

    /**
     * @var array
     */
    protected $fillable = [
        'completed',
        'teacher_mark',
        'problem_id',
        'user_id',
        'answer_id'
    ];

    protected $casts = [
        'completed' => 'boolean'
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
    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }

    /**
     * @return BelongsTo
     */
    public function problem(): BelongsTo
    {
        return $this->belongsTo(Problem::class);
    }
}
