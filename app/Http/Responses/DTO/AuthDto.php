<?php

namespace App\Http\Responses\DTO;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthDto implements Responsable
{
    public function __construct(
        public bool    $success = true,
        public ?string $token = null,
        public mixed   $data = null,
        public ?string $message = null,
        public int     $statusCode = 200
    )
    {
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'data'    => $this->data,
            'token'    => $this->token,
            'message' => $this->message,
        ];
    }

    public function toResponse($request): JsonResponse|Response
    {
        return response()->json($this->toArray(), $this->statusCode);
    }
}
