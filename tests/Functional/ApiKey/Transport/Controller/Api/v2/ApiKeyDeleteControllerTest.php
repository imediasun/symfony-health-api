<?php

declare(strict_types=1);

namespace App\Tests\Functional\ApiKey\Transport\Controller\Api\v2;

use App\ApiKey\Application\Resource\ApiKeyFindOneResource;
use App\ApiKey\Domain\Entity\ApiKey;
use App\General\Domain\Utils\JSON;
use App\General\Transport\Utils\Tests\WebTestCase;
use App\Tests\Functional\ApiKey\Transport\Controller\Api\v2\Traits\ApiKeyHelper;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class ApiKeyDeleteControllerTest
 *
 * @package App\Tests
 */
class ApiKeyDeleteControllerTest extends WebTestCase
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
     * @testdox Test that `DELETE /v2/api_key/{id}` returns forbidden error for non-root user.
     *
     * @throws Throwable
     */
    public function testThatDeleteActionForNonRootUserReturnsForbiddenResponse(): void
    {
        $client = $this->getTestClient('john-admin', 'password-admin');

        $client->request(method: 'DELETE', uri: $this->baseUrl . '/' . $this->apiKey->getId());
        $response = $client->getResponse();
        $content = $response->getContent();
        self::assertNotFalse($content);
        self::assertSame(Response::HTTP_FORBIDDEN, $response->getStatusCode(), "Response:\n" . $response);

        // let's check that row wasn't deleted inside db.
        /** @var ApiKey|null $apiKey */
        $apiKey = $this->apiKeyFindOneResource->findOne($this->apiKey->getId());
        self::assertInstanceOf(ApiKey::class, $apiKey);
    }

    /**
     * @testdox Test that `DELETE /v2/api_key/{id}` for the Root user returns success response.
     *
     * @throws Throwable
     */
    public function testThatDeleteActionForRootUserReturnsSuccessResponse(): void
    {
        $client = $this->getTestClient('john-root', 'password-root');

        $client->request('DELETE', $this->baseUrl . '/' . $this->apiKey->getId());
        $response = $client->getResponse();
        $content = $response->getContent();
        self::assertNotFalse($content);
        self::assertSame(Response::HTTP_OK, $response->getStatusCode(), "Response:\n" . $response);
        $responseData = JSON::decode($content, true);
        self::assertArrayHasKey('id', $responseData);
        self::assertArrayHasKey('token', $responseData);
        self::assertArrayHasKey('description', $responseData);
        self::assertEquals($this->apiKey->getId(), $responseData['id']);

        // let's check that row deleted inside db.
        /** @var ApiKey|null $apiKey */
        $apiKey = $this->apiKeyFindOneResource->findOne($this->apiKey->getId());
        self::assertNull($apiKey);
    }
}
