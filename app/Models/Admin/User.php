<?php

namespace App\Models\Admin;

use App\Models\Admin\Commune;
use App\Models\Admin\District;
use App\Models\Admin\Image;
use App\Models\Admin\Product;
use App\Models\Admin\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

class User extends Authenticatable implements HasMedia
{
    use HasFactory, Notifiable, SoftDeletes, InteractsWithMedia;

    protected $fillable = [
        'category_id',
        'district_id',
        'commune_id',
        'name',
        'full_name',
        'phone',
        'address',
        'email',
        'email_verified_at',
        'birthday',
        'description',
        'ip',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthday' => 'datetime',
    ];

    // Relationships
    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function commune()
    {
        return $this->belongsTo(Commune::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
    }

    public function checkPermissionAccess($permissionCheck)
    {
        // Lấy các quyền của user đang đăng nhập
        $roles = $this->roles;

        foreach ($roles as $role) {
            $permissions = $role->permissions;
            // Kiểm tra từng quyền một
            foreach ($permissions as $permission) {
                if ($permission->key_code === $permissionCheck) {
                    return true; // Nếu tìm thấy quyền, trả về true
                }
            }
        }

        return false; // Nếu không tìm thấy quyền, trả về false
    }

    public function hasRole($roleName)
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    protected function updatedAtVi(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->updated_at)->format('d.m.Y h:i'),
        );
    }

    protected function createdAtVi(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->created_at)->format('d.m.Y h:i'),
        );
    }

    protected function birthdayAtVi(): Attribute
    {
        return Attribute::make(
            get: fn() => Carbon::parse($this->birthday)->format('d.m.Y'),
        );
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->status == 1) {
            return '<span class="text-green-700 text-xs px-3 py-1 rounded-full font-bold" style="background-color: aquamarine">Kích hoạt</span>';
        } else {
            return '<span class="text-white text-xs px-3 py-1 rounded-full font-bold" style="background-color: #ff5b5b">Không kích hoạt</span>';
        }
    }
}
