<?php

namespace App\Repositories;

use App\Attributes\Throws;
use App\Models\User;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Exception;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpFoundation\Response;

readonly class AuthRepository implements AuthRepositoryInterface
{
    public function __construct(private User $model)
    {
    }


    #[Throws(exceptionClass: Exception::class)]
    public function create(array $data): ?User
    {
        try {
            return $this->model->query()->create($data);
        } catch (QueryException $e) {
            throw new Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Throws(exceptionClass: Exception::class)]
    public function update(int $id, array $data): bool
    {
        try {
            return $this->model->update($data);
        } catch (QueryException $e) {
            throw new Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }


    #[Throws(exceptionClass: Exception::class)]
    public function getByEmail(string $email):User
    {
        try {
            $query = $this->model->query();
            return $query->where('email', $email)->first();
        } catch (QueryException $e) {
            throw new Exception($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
