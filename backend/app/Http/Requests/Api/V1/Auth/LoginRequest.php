<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public const EMAIL = 'email';
    public const PASSWORD = 'password';
    public const AS = 'as';

    /**
     * Determine if the user is authorized to make this request.
     */
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
            self::EMAIL => sprintf(
                'required|email|exists:%s,%s',
                User::TABLE,
                User::EMAIL
            ),
            self::PASSWORD => 'required',
            self::AS => sprintf(
                'nullable|string|exists:%s,%s',
                Role::TABLE,
                Role::SLUG
            ),
        ];
    }

    public function getEmail(): string
    {
        return $this->{self::EMAIL};
    }

    public function getPassword(): string
    {
        return $this->{self::PASSWORD};
    }

    public function getRole(): string
    {
        return $this->{self::AS} ?? '';
    }
}
