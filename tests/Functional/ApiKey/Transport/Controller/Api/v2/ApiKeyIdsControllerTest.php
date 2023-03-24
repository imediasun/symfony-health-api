<?php

declare(strict_types=1);

namespace App\Tests\Functional\ApiKey\Transport\Controller\Api\v2;

use App\General\Domain\Utils\JSON;
use App\General\Transport\Utils\Tests\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class ApiKeyIdsControllerTest
 *
 * @package App\Tests
 */
class ApiKeyIdsControllerTest extends WebTestCase
{
    private string $baseUrl = self::API_URL_PREFIX . '/v2/api_key/ids';

    /**
     * @testdox Test that `GET /v2/api_key/ids` returns forbidden error for non-root user.
     *
     * @throws Throwable
     */
    public function testThatIdsActionForNonRootUserReturnsForbiddenResponse(): void
    {
        $client = $this->getTestClient('john-admin', 'password-admin');

        $client->request('GET', $this->baseUrl);
        $response = $client->getResponse();
        $content = $response->getContent();
        self::assertNotFalse($content);
        self::assertSame(Response::HTTP_FORBIDDEN, $response->getStatusCode(), "Response:\n" . $response);
    }

    /**
     * @testdox Test that `GET /v2/api_key/ids` for the Root user returns success response.
     *
     * @throws Throwable
     */
    public function testThatIdsActionForRootUserReturnsSuccessResponse(): void
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
        self::assertIsString($responseData[0]);
    }
}
