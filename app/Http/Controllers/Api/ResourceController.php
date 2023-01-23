<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ResourceRepository;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * @group Resources management
 *
 * Handles work with resources.
 */
class ResourceController extends Controller
{
    public function __construct(private ResourceRepository $repository)
    {
    }

    /**
     * List resources.
     *
     * List all available resources.
     * @authenticated
     */
    public function list()
    {
        try {
            return response()->json($this->repository->list(), Response::HTTP_OK);

        } catch (Throwable $exception) {
            return response()->json([
                'message' => 'Falha ao buscar a lista de recursos'
            ], Response::HTTP_BAD_REQUEST);
        }
    }
}
