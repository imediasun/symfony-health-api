<?php

declare(strict_types=1);

namespace App\General\Transport\Rest\Traits\Actions\Anon;

use App\General\Transport\Rest\Traits\Methods\IdsMethod;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * Trait IdsAction
 *
 * Trait to add 'idsAction' for REST controllers for anonymous users.
 *
 * @see \App\General\Transport\Rest\Traits\Methods\IdsMethod for detailed documents.
 *
 * @package App\General
 */
trait IdsAction
{
    use IdsMethod;

    /**
     * Find ids list, accessible for anonymous users.
     *
     * @OA\Response(
     *     response=200,
     *     description="success",
     *     @OA\JsonContent(
     *         type="array",
     *         @OA\Items(type="string"),
     *     ),
     * )
     *
     * @throws Throwable
     */
    #[Route(
        path: '/ids',
        methods: [Request::METHOD_GET],
    )]
    public function idsAction(Request $request): Response
    {
        return $this->idsMethod($request);
    }
}
