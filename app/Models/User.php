<?php

namespace App\Models;

use Hash;
use App\Traits\ModelWithQrCode;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes, ModelWithQrCode;

    const ROLE_STUDENT = 'student';
    const ROLE_PARENT = 'parent';
    const ROLE_TUTOR = 'tutor';
    const ROLE_TEACHER = 'teacher';
    const ROLE_ADMIN = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
        'avatar_id',
        'phone'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    const ROLES = [
        self::ROLE_STUDENT,
        self::ROLE_PARENT,
        self::ROLE_TUTOR,
        self::ROLE_TEACHER,
        self::ROLE_ADMIN
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (self $entry) {
            foreach ($entry->myGroups as $group) {
                $group->delete();
            }
        });
    }

    /**
     * @param string $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * @return string
     */
    public function getUserTokenAttribute(): string
    {
        return $this->createToken('site-token')->plainTextToken;
    }

    /**
     * @return string
     */
    public function getUserAvatarAttribute(): string
    {
        return optional($this->avatar)->fileLink;
    }

    /**
     * @return BelongsTo
     */
    public function avatar(): BelongsTo
    {
        return $this->belongsTo(Attachment::class);
    }

    /**
     * @return HasMany
     */
    public function myGroups(): HasMany
    {
        return $this->hasMany(Group::class);
    }

    /**
     * @return HasMany
     */
    public function mySolutions(): HasMany
    {
        return $this->hasMany(Solution::class);
    }

    /**
     * @return HasMany | Answer
     */
    public function myAnswers(): HasMany
    {
        return $this->hasMany(Answer::class)->orderByDesc('created_at');
    }

    /**
     * @return HasMany
     */
    public function myMessages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * @return HasMany
     */
    public function myAttachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * @return BelongsToMany
     */
    public function assignments(): BelongsToMany
    {
        return $this->belongsToMany(Assignment::class, 'answers')->withTimestamps();
    }

    /**
     * @return HasMany
     */
    public function myAssignments(): HasMany
    {
        return $this->hasMany(Assignment::class)->orderByDesc('created_at');
    }

    /**
     * @return BelongsToMany
     */
    public function userGroups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class)->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_user',
            'child_id', 'parent_id')->withTimestamps();
    }

    /**
     * @return BelongsToMany
     */
    public function children(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_user',
            'parent_id', 'child_id')->withTimestamps();
    }
}
