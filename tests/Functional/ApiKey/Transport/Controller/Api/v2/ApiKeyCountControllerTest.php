<?php

declare(strict_types=1);

namespace App\Tests\Functional\ApiKey\Transport\Controller\Api\v2;

use App\General\Domain\Utils\JSON;
use App\General\Transport\Utils\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class ApiKeyCountControllerTest
 *
 * @package App\Tests
 */
class ApiKeyCountControllerTest extends WebTestCase
{
    private string $baseUrl = self::API_URL_PREFIX . '/v2/api_key/count';

    /**
     * @testdox Test that `GET /v2/api_key/count` returns forbidden error for non-root user.
     *
     * @throws Throwable
     */
    public function testThatCountActionForNonRootUserReturnsForbiddenResponse(): void
    {
        $client = $this->getTestClient('john-admin', 'password-admin');

        $client->request('GET', $this->baseUrl);
        $response = $client->getResponse();
        $content = $response->getContent();
        self::assertNotFalse($content);
        self::assertSame(Response::HTTP_FORBIDDEN, $response->getStatusCode(), "Response:\n" . $response);
    }

    /**
     * @testdox Test that `GET /v2/api_key/count` for the Root user returns success response.
     *
     * @throws Throwable
     */
    public function testThatCountActionForRootUserReturnsSuccessResponse(): void
    {
        $client = $this->getTestClient('john-root', 'password-root');

        $client->request(method: 'GET', uri: $this->baseUrl);
        $response = $client->getResponse();
        $content = $response->getContent();
        self::assertNotFalse($content);
        self::assertSame(Response::HTTP_OK, $response->getStatusCode(), "Response:\n" . $response);
        $responseData = JSON::decode($content, true);
        self::assertArrayHasKey('count', $responseData);
        self::assertGreaterThan(0, $responseData['count']);
    }
}
