<?php

namespace App\Http\Responses\DTO;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseDto implements Responsable
{
    public function __construct(
        public bool    $success = true,
        public mixed   $data = null,
        public ?string $message = null,
        public int     $statusCode = Response::HTTP_OK
    )
    {
    }

    public function toArray(): array
    {
        return [
            'success' => $this->success,
            'data'    => $this->data,
            'message' => $this->message,
        ];
    }

    public function toResponse($request): JsonResponse
    {
        return response()->json($this->toArray(), $this->statusCode);
    }
}
