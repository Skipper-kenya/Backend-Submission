<?php

declare(strict_types=1);

namespace App\Http\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

trait RespondsWithJson
{
    /** Return a successful JSON response with optional data. */
    protected function success(mixed $data = null, int $status = 200): JsonResponse
    {
        $payload = $data instanceof JsonResource || $data instanceof ResourceCollection
            ? $data->response()->getData(true)
            : ['data' => $data];

        return response()->json($payload, $status);
    }

    /** Return an error JSON response. */
    protected function error(string $message, int $status = 400, array $errors = []): JsonResponse
    {
        $payload = array_filter([
            'message' => $message,
            'errors' => $errors ?: null,
        ]);

        return response()->json($payload, $status);
    }
}
