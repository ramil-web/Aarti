<?php

namespace App\Http\Controllers;

use App\Attributes\Throws;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Responses\DTO\AuthDto;
use App\Services\AuthService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct(protected AuthService $service)
    {
    }

    #[Throws(exceptionClass: Exception::class)]
    public function register(RegisterRequest $request): AuthDto
    {
        $user = $this->service->create($request->validated());
        return new AuthDto(token: $user['token'], data: $user['data'], statusCode: Response::HTTP_CREATED);
    }

    public function login(LoginRequest $request): AuthDto
    {
        return new AuthDto(token: $this->service->login($request->validated()));
    }

    #[Throws(exceptionClass: Exception::class)]
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    #[Throws(exceptionClass: Exception::class)]
    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}
