<?php

namespace App\Repositories;

use App\Attributes\Throws;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;

readonly class UserRepository implements UserRepositoryInterface
{
    public function __construct(private User $user)
    {
    }

    #[Throws(exceptionClass: Exception::class)]
    public function create(array $data): User
    {
        try {
            return $this->user->query()->create($data);
        } catch (QueryException $e) {
            throw new Exception("Failed to create user: {$e->getMessage()}", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Throws(exceptionClass: Exception::class)]
    public function findByEmail(string $email): ?User
    {
        try {
            return $this->user->query()->where('email', $email)->first();
        } catch (QueryException $e) {
            throw new Exception("Failed to find user: {$e->getMessage()}", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Throws(exceptionClass: Exception::class)]
    public function findById(int $id): ?User
    {
        try {
            return $this->user->query()->find($id);
        } catch (QueryException $e) {
            throw new Exception("Failed to find user: {$e->getMessage()}", Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
