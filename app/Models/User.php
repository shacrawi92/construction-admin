<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'phone', 'locale', 'avatar', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────

    public function foreman()
    {
        return $this->hasOne(Foreman::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'created_by');
    }

    public function assignedProjects()
    {
        return $this->hasMany(Project::class, 'assigned_foreman_id');
    }

    public function laborLogs()
    {
        return $this->hasMany(LaborLog::class, 'foreman_id');
    }

    public function materialRequests()
    {
        return $this->hasMany(MaterialRequest::class, 'foreman_id');
    }

    // ── Scopes ─────────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForemen($query)
    {
        return $query->role('foreman');
    }

    public function scopeWorkers($query)
    {
        return $query->role('worker');
    }

    // ── Helpers ────────────────────────────────────────────────────────────────

    public function isSaasAdmin(): bool   { return $this->email === 'admin@shacrawi.com' || $this->hasRole('saas_admin'); }
    public function isTenantAdmin(): bool { return $this->hasRole('tenant_admin'); }
    public function isAdmin(): bool       { return $this->hasRole('admin') || $this->isSaasAdmin() || $this->isTenantAdmin(); }
    public function isForeman(): bool     { return $this->hasRole('foreman'); }
    public function isWorker(): bool      { return $this->hasRole('worker'); }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active && (
            $this->isAdmin() || 
            str_ends_with($this->email, '@shacrawi.com') || 
            str_ends_with($this->email, '@abyssinia.et') || 
            str_ends_with($this->email, '@hornconstruction.so') || 
            str_ends_with($this->email, '@sunsetpremier.com')
        );
    }
}
