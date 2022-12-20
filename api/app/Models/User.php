<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    const DEFAULT_PERMISSIONS = [
        'users' => 'Usuários',
        'vehicles' => 'Veículos',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'avatar',
        'created_by',
        'email_verified_at',
        'permissions'
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
        'permissions' => 'array'
    ];

    private $hashble = null;

    public function __construct(...$args)
    {
        parent::__construct(...$args);
        $this->hashble = "{$this->email}.{$this->getKey()}";
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() : array
    {
        return [];
    }

    public function setPassword(string $password) : self
    {
        $this->password = Hash::make($password);
        return $this;
    }

    public function getConfirmationCode() : string
    {
        return str_replace(['.', '%'], ['_', '-'], urlencode(Hash::make($this->hashble)));
    }

    public function compareConfirmationCode(string $hash) : bool
    {
        return Hash::check($this->hashble, urldecode(str_replace(['_', '-'], ['.', '%'], $hash)));
    }

    public function setPermissions(array $permissions = self::DEFAULT_PERMISSIONS) : self
    {
        $this->permissions = json_encode($permissions);
        return $this;
    }

    public function getPermissions() : array
    {
        return json_decode($this->permissions, true);
    }
}
