<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * Note: `password` and `status` are intentionally excluded
     * for security. Set them explicitly via forceFill() or
     * dedicated methods in your controllers.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'photo',
        'email',
        'phone',
        'gender',
        'date_of_birth',
        'organization',
        'job_title',
        'bio',
        'address',
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'date_of_birth' => 'date',
            'password' => 'hashed',
            'status' => 'boolean',
        ];
    }

    /* -----------------------------------------------------------------
     |  Model Events
     | ----------------------------------------------------------------- */

    /**
     * Boot the model.
     *
     * - Auto-fills `name` from `first_name` + `last_name` (with email fallback).
     * - Assigns the default `member` role on creation.
     */
    protected static function booted(): void
    {
        // Auto-fill `name` before saving
        static::saving(function (User $user) {
            if ($user->first_name || $user->last_name) {
                $user->name = trim("{$user->first_name} {$user->last_name}");
            }

            if (empty($user->name)) {
                $user->name = $user->email
                    ? strstr($user->email, '@', true)
                    : 'User';
            }
        });

        // Assign default role after creation
        static::created(function (User $user) {
            if ($user->roles()->count() === 0) {
                $user->assignRole('member');
            }
        });
    }

    /* -----------------------------------------------------------------
     |  Relationships
     | ----------------------------------------------------------------- */

    /**
     * Books borrowed by this user.
     */
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Reviews written by this user.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /* -----------------------------------------------------------------
     |  Helpers
     | ----------------------------------------------------------------- */

    /**
     * Get all social links as an array (only non-empty ones).
     *
     * @return array<string, string>
     */
    public function socialLinks(): array
    {
        return array_filter([
            'facebook' => $this->facebook,
            'twitter' => $this->twitter,
            'linkedin' => $this->linkedin,
            'instagram' => $this->instagram,
        ]);
    }

    /**
     * Check if the user has any social link set.
     */
    public function hasSocialLinks(): bool
    {
        return !empty($this->socialLinks());
    }

    /**
     * Get the user's avatar URL or a default fallback.
     */
    public function avatarUrl(): string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }

        $name = urlencode($this->displayName());

        return "https://ui-avatars.com/api/?name={$name}&size=200&background=0d6efd&color=ffffff&bold=true";
    }

    /**
     * Get the user's full display name.
     */
    public function displayName(): string
    {
        return $this->name
            ?: trim("{$this->first_name} {$this->last_name}")
            ?: 'Anonymous';
    }

    /**
     * Check if the user is verified.
     */
    public function isVerified(): bool
    {
        return !is_null($this->email_verified_at);
    }
}