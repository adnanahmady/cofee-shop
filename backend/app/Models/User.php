<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Contracts\Models\Fields\IdContract;
use App\Contracts\Models\RoleUserContract;
use App\ValueObjects\Users\NameInterface;
use App\ValueObjects\Users\NameObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements IdContract
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    public const TABLE = 'users';
    public const FIRST_NAME = 'first_name';
    public const LAST_NAME = 'last_name';
    public const EMAIL = 'email';
    public const PASSWORD = 'password';

    protected $table = self::TABLE;

    protected $primaryKey = self::ID;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        self::FIRST_NAME,
        self::LAST_NAME,
        self::EMAIL,
        self::PASSWORD,
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        self::PASSWORD,
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        self::PASSWORD => 'hashed',
    ];

    private null|NameInterface $name = null;

    public function getId(): int
    {
        return $this->{self::ID};
    }

    public function getName(): NameInterface
    {
        if (null === $this->name) {
            $this->name = new NameObject(
                $this->{self::FIRST_NAME},
                $this->{self::LAST_NAME}
            );
        }

        return $this->name;
    }

    public function setName(NameInterface $name): self
    {
        $this->name = $name;
        $this->{self::FIRST_NAME} = $name->getFirstName();
        $this->{self::LAST_NAME} = $name->getLastName();

        return $this;
    }

    public function getEmail(): string
    {
        return $this->{self::EMAIL};
    }

    public function setEmail(string $email): self
    {
        $this->{self::EMAIL} = $email;

        return $this;
    }

    public function getPassword(): string
    {
        return $this->{self::PASSWORD};
    }

    public function setPassword(string $password): self
    {
        $this->{self::PASSWORD} = $password;

        return $this;
    }

    public function getCreatedAt(): string
    {
        return $this->{self::CREATED_AT};
    }

    public function getUpdatedAt(): string
    {
        return $this->{self::UPDATED_AT};
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            RoleUserContract::TABLE
        )->withTimestamps();
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
