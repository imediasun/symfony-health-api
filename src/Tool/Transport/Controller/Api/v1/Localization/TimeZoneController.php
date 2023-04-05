<?php

declare(strict_types=1);

namespace App\Tool\Transport\Controller\Api\v1\Localization;

use App\Tool\Application\Service\LocalizationService;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 * Class TimezoneController
 *
 * @OA\Get(security={})
 *
 * @OA\Tag(name="Localization")
 *
 * @package App\Tool
 */
#[AsController]
class TimeZoneController
{
    public function __construct(
        private readonly LocalizationService $localization,
    ) {
    }

    /**
     * Endpoint action to get list of supported timezones. This is for use to
     * choose what timezone your frontend application can use within its date,
     * time, datetime, etc. formatting.
     *
     * @OA\Response(
     *     response=200,
     *     description="List of timezone objects.",
     *     @OA\JsonContent(
     *         type="array",
     *         @OA\Items(
     *             type="object",
     *             @OA\Property(
     *                 property="timezone",
     *                 type="string",
     *                 example="Europe",
     *                 description="Africa,America,Antarctica,Arctic,Asia,Atlantic,Australia,Europe,Indian,Pacific,UTC."
     *             ),
     *             @OA\Property(
     *                 property="identier",
     *                 type="string",
     *                 example="Europe/Kyiv",
     *                 description="Timezone identifier that you can use with other librariers."
     *             ),
     *             @OA\Property(
     *                 property="offset",
     *                 type="string",
     *                 example="GMT+2:00",
     *                 description="GMT offset of identifier."
     *             ),
     *             @OA\Property(
     *                 property="value",
     *                 type="string",
     *                 example="Europe/Kyiv",
     *                 description="User friendly value of identifier value eg. '_' characters are replaced by space."
     *             ),
     *         ),
     *     ),
     * )
     *
     * @throws Throwable
     */
    #[Route(
        path: '/v1/localization/timezone',
        methods: [Request::METHOD_GET],
    )]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse($this->localization->getTimezones());
    }
}
