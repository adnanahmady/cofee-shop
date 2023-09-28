<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public const EMAIL = 'email';
    public const PASSWORD = 'password';
    public const FIRST_NAME = 'first_name';
    public const LAST_NAME = 'last_name';

    /** Determine if the user is authorized to make this request. */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string>
     */
    public function rules(): array
    {
        return [
            self::FIRST_NAME => 'required',
            self::LAST_NAME => 'required',
            self::EMAIL => sprintf(
                'required|email|unique:%s,%s',
                User::TABLE,
                User::EMAIL
            ),
            self::PASSWORD => 'required',
        ];
    }

    public function getFirstName(): string
    {
        return $this->{self::FIRST_NAME};
    }

    public function getLastName(): string
    {
        return $this->{self::LAST_NAME};
    }

    public function getEmail(): string
    {
        return $this->{self::EMAIL};
    }

    public function getPassword(): string
    {
        return $this->{self::PASSWORD};
    }
}
