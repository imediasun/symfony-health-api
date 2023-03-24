<?php

declare(strict_types=1);

namespace App\Tests\Functional\ApiKey\Transport\Controller\Api\v2;

use App\ApiKey\Infrastructure\DataFixtures\ORM\LoadApiKeyData;
use App\General\Domain\Utils\JSON;
use App\General\Transport\Utils\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class ApiKeyViewControllerTest
 *
 * @package App\Tests
 */
class ApiKeyViewControllerTest extends WebTestCase
{
    private string $baseUrl = self::API_URL_PREFIX . '/v2/api_key';

    /**
     * @testdox Test that `GET /v2/api_key/{id}` returns forbidden error for non-root user.
     *
     * @throws Throwable
     */
    public function testThatFindOneActionForNonRootUserReturnsForbiddenResponse(): void
    {
        $client = $this->getTestClient('john-admin', 'password-admin');

        $client->request('GET', $this->baseUrl . '/' . LoadApiKeyData::$uuids['-logged']);
        $response = $client->getResponse();
        $content = $response->getContent();
        self::assertNotFalse($content);
        self::assertSame(Response::HTTP_FORBIDDEN, $response->getStatusCode(), "Response:\n" . $response);
    }

    /**
     * @testdox Test that `GET /v2/api_key/{id}` for the Root user returns success response.
     *
     * @throws Throwable
     */
    public function testThatFindOneActionForRootUserReturnsSuccessResponse(): void
    {
        $client = $this->getTestClient('john-root', 'password-root');

        $client->request('GET', $this->baseUrl . '/' . LoadApiKeyData::$uuids['-logged']);
        $response = $client->getResponse();
        $content = $response->getContent();
        self::assertNotFalse($content);
        self::assertSame(Response::HTTP_OK, $response->getStatusCode(), "Response:\n" . $response);
        $responseData = JSON::decode($content, true);
        self::assertArrayHasKey('id', $responseData);
        self::assertArrayHasKey('token', $responseData);
        self::assertArrayHasKey('description', $responseData);
        self::assertEquals(LoadApiKeyData::$uuids['-logged'], $responseData['id']);
    }
}
