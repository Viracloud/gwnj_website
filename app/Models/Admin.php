<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_SUPER_ADMIN = 'super_admin';
    const ROLE_AUTHOR = 'author';
    const ROLE_VIEWER = 'viewer';

    protected $fillable = ['name', 'email', 'photo', 'password', 'role'];

    public static function roleLabels(): array
    {
        return [
            self::ROLE_SUPER_ADMIN => 'Super Admin',
            self::ROLE_AUTHOR => 'Author',
            self::ROLE_VIEWER => 'Viewer',
        ];
    }

    public function roleLabel(): string
    {
        return self::roleLabels()[$this->role] ?? $this->role;
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isAuthor(): bool
    {
        return $this->role === self::ROLE_AUTHOR;
    }

    public function isViewer(): bool
    {
        return $this->role === self::ROLE_VIEWER;
    }

    /** True if this admin is allowed to create/edit/delete content (super_admin or author). */
    public function canEditContent(): bool
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_AUTHOR]);
    }
}
