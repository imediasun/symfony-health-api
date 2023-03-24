<?php

declare(strict_types=1);

namespace App\Tests\Functional\ApiKey\Transport\Controller\Api\v2;

use App\General\Domain\Utils\JSON;
use App\General\Transport\Utils\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class ApiKeyListControllerTest
 *
 * @package App\Tests
 */
class ApiKeyListControllerTest extends WebTestCase
{
    private string $baseUrl = self::API_URL_PREFIX . '/v2/api_key';

    /**
     * @testdox Test that `GET /v2/api_key` returns forbidden error for non-root user.
     *
     * @throws Throwable
     */
    public function testThatFindActionForNonRootUserReturnsForbiddenResponse(): void
    {
        $client = $this->getTestClient('john-admin', 'password-admin');

        $client->request('GET', $this->baseUrl);
        $response = $client->getResponse();
        $content = $response->getContent();
        self::assertNotFalse($content);
        self::assertSame(Response::HTTP_FORBIDDEN, $response->getStatusCode(), "Response:\n" . $response);
    }

    /**
     * @testdox Test that `GET /v2/api_key` for the Root user returns success response.
     *
     * @throws Throwable
     */
    public function testThatFindActionForRootUserReturnsSuccessResponse(): void
    {
        $client = $this->getTestClient('john-root', 'password-root');

        $client->request(method: 'GET', uri: $this->baseUrl);
        $response = $client->getResponse();
        $content = $response->getContent();
        self::assertNotFalse($content);
        self::assertSame(Response::HTTP_OK, $response->getStatusCode(), "Response:\n" . $response);
        $responseData = JSON::decode($content, true);
        self::assertIsArray($responseData);
        self::assertGreaterThan(5, $responseData);
        self::assertIsArray($responseData[0]);
        self::assertArrayHasKey('id', $responseData[0]);
        self::assertArrayHasKey('token', $responseData[0]);
        self::assertArrayHasKey('description', $responseData[0]);
    }
}
