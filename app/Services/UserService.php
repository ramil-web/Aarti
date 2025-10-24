<?php

namespace App\Services;

use App\Attributes\Throws;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Hash;

readonly class UserService
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    #[Throws(exceptionClass: Exception::class)]
    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return $this->userRepository->create($data);
    }

    #[Throws(exceptionClass: Exception::class)]
    public function login(array $credentials): ?User
    {
        $user = $this->userRepository->findByEmail($credentials['email']);
        if($user && Hash::check($credentials['password'], $user->password)){
            return $user;
        }
        return null;
    }
}
