<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Get users liked by the user.
     */
    public function likedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes', 'liked_by_user_id', 'liked_user_id', 'id', 'id')->withTimestamps();
    }

    /**
     * Get users who liked the user.
     */
    public function likedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes', 'liked_user_id', 'liked_by_user_id', 'id', 'id')->withTimestamps();
    }

    /**
     * Get pairs.
     */
    public function pairs(): BelongsToMany
    {
        return $this->likedUsers()->whereRelation('likedUsers', 'liked_user_id', $this->id);
    }

    /**
     * Check if the user is liked by other user.
     */
    public function likedByUser(User $user): bool
    {
        return $this->likedByUsers()->wherePivot('liked_by_user_id', $user->id)->exists();
    }
}
