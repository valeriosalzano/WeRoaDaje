<?php namespace App\Http\Library;

use Illuminate\Http\JsonResponse;

trait ApiHelpers
{
    protected function isAdmin($user): bool
    {
        if (!empty($user)) {
            return $user->tokenCan('admin');
        }

        return false;
    }

    protected function isEditor($user): bool
    {

        if (!empty($user)) {
            return $user->tokenCan('editor');
        }

        return false;
    }

    protected function onSuccess($data, $message = '', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => substr((string)$code,0,1) == '2',
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function onError(int $code, string $message = '', array $errors = []): JsonResponse
    {
        return response()->json([
            'success' => substr((string)$code,0,1) == '2',
            'message' => $message,
            'errors' => $errors
        ], $code);
    }
}