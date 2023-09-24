<?php

namespace App\Models;

use App\Contracts\Models\Fields\IdContract;
use App\Interfaces\IdInterface;
use App\Traits\Models\Fields\HasIdTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model implements IdContract
{
    use HasFactory;
    use HasIdTrait;

    public const TABLE = 'addresses';
    public const TITLE = 'title';
    public const USER = 'user_id';
    public const CITY = 'city';
    public const STREET = 'street';
    public const PLATE_NUMBER = 'plate_number';
    public const POSTAL_CODE = 'postal_code';
    public const DESCRIPTION = 'description';

    protected $table = self::TABLE;

    protected $fillable = [
        self::TITLE,
        self::USER,
        self::CITY,
        self::STREET,
        self::PLATE_NUMBER,
        self::POSTAL_CODE,
        self::DESCRIPTION,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTitle(): string
    {
        return $this->{self::TITLE};
    }

    public function getUser(): int
    {
        return $this->{self::USER};
    }

    public function getCity(): string
    {
        return $this->{self::CITY};
    }

    public function getStreet(): string
    {
        return $this->{self::STREET};
    }

    public function getPlateNumber(): string
    {
        return $this->{self::PLATE_NUMBER};
    }

    public function getPostalCode(): string
    {
        return $this->{self::POSTAL_CODE};
    }

    public function getDescription(): string
    {
        return $this->{self::DESCRIPTION};
    }

    public function setTitle(string $title): void
    {
        $this->{self::TITLE} = $title;
    }

    public function setUser(User|IdInterface $user): void
    {
        $this->{self::USER} = $user->getId();
    }

    public function setCity(string $city): void
    {
        $this->{self::CITY} = $city;
    }

    public function setStreet(string $street): void
    {
        $this->{self::STREET} = $street;
    }

    public function setPlateNumber(string $plateNumber): void
    {
        $this->{self::PLATE_NUMBER} = $plateNumber;
    }

    public function setPostalCode(string $postalCode): void
    {
        $this->{self::POSTAL_CODE} = $postalCode;
    }

    public function setDescription(string $description): void
    {
        $this->{self::DESCRIPTION} = $description;
    }
}
