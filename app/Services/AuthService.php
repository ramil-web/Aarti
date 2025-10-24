<?php

namespace App\Services;

use App\Attributes\Throws;
use App\Repositories\AuthRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

readonly class AuthService
{
    public function __construct(private AuthRepository $repository)
    {
    }

    #[Throws(exceptionClass: Exception::class)]
    public function create(mixed $data): array
    {
        $data = [
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'] ?? 'user'
        ];
        $user = $this->repository->create($data);
        $token = $user->createToken('auth_token')->plainTextToken;
        return [
            'data'  => $user,
            'token' => $token
        ];
    }

    #[Throws(exceptionClass: Exception::class)]
    public function login(array $data)
    {
        if (!Auth::attempt($data)) {
            throw new Exception('Invalid credentials', Response::HTTP_UNAUTHORIZED);
        }

        $user = Auth::user();
        return $user->createToken('api-token')->plainTextToken;
    }

}
