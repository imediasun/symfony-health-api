<?php

declare(strict_types=1);

namespace App\Tests\Functional\ApiKey\Transport\Controller\Api\v2;

use App\ApiKey\Application\Resource\ApiKeyFindOneResource;
use App\ApiKey\Domain\Entity\ApiKey;
use App\General\Transport\Utils\Tests\WebTestCase;
use App\Tests\Functional\ApiKey\Transport\Controller\Api\v2\Traits\ApiKeyHelper;
use App\User\Infrastructure\DataFixtures\ORM\LoadUserGroupData;
use Generator;
use Throwable;

/**
 * Class ApiKeyUpdateControllerTest
 *
 * @package App\Tests
 */
class ApiKeyUpdateControllerTest extends WebTestCase
{
    use ApiKeyHelper;

    private string $baseUrl = self::API_URL_PREFIX . '/v2/api_key';
    private ApiKey $apiKey;
    private ApiKeyFindOneResource $apiKeyFindOneResource;

    /**
     * @throws Throwable
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->apiKeyFindOneResource = static::getContainer()->get(ApiKeyFindOneResource::class);
        $this->apiKey = $this->findOrCreateApiKey();
    }

    /**
     * @testdox Test that `PUT /v2/api_key/{id}` returns forbidden error for non-root user.
     *
     * @throws Throwable
     */
    public function testThatPutActionForNonRootUserReturnsForbiddenResponse(): void
    {
        $this->checkActionForNonRootUserReturnsForbiddenResponse('PUT', 'test api key updated');
    }

    /**
     * @testdox Test that `PUT /v2/api_key/{id}` with wrong data returns validation error.
     *
     * @dataProvider dataProviderWithIncorrectData
     *
     * @param array<string, string|array<string>> $requestData
     *
     * @throws Throwable
     */
    public function testThatPatchActionForRootUserWithWrongDataReturnsValidationErrorResponse(
        array $requestData,
        string $error
    ): void {
        $this->checkActionForRootUserWithWrongDataReturnsValidationErrorResponse('PUT', $requestData, $error);
    }

    /**
     * @testdox Test that `PUT /v2/api_key/{id}` for the Root user returns success response.
     *
     * @throws Throwable
     */
    public function testThatPatchActionForRootUserReturnsSuccessResponse(): void
    {
        $this->checkActionForRootUserReturnsSuccessResponse('PUT', 'test api key updated');
    }

    /**
     * @return Generator<array{0: array<string, string|array<string>>, 1: string}>
     */
    public function dataProviderWithIncorrectData(): Generator
    {
        yield [
            [
                'description' => '',
                'userGroups' => [
                    LoadUserGroupData::$uuids['Role-api'],
                ],
            ],
            'This value should not be blank.',
        ];
        yield [
            [
                'description' => 'test api key updated',
                'userGroups' => [
                    '90000000-0000-1000-8000-900000000009',
                ],
            ],
            'id(90000000-0000-1000-8000-900000000009) was not found',
        ];
    }
}
