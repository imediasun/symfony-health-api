<?php

declare(strict_types=1);

namespace App\Tests\Functional\User\Transport\Controller\Api\v1\Profile;

use App\General\Domain\Utils\JSON;
use App\General\Transport\Utils\Tests\WebTestCase;
use App\Role\Application\Security\RolesService;
use App\User\Application\Resource\UserResource;
use App\User\Domain\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class RolesControllerTest
 *
 * @package App\Tests
 */
class RolesControllerTest extends WebTestCase
{
    private string $baseUrl = self::API_URL_PREFIX . '/v1/profile';

    /**
     * @testdox Test that `GET /api/v1/profile/roles` for the `john-root` user returns success response.
     *
     * @throws Throwable
     */
    public function testThatGetUserRolesActionForRootUserReturnsSuccessResponse(): void
    {
        $client = $this->getTestClient('john-root', 'password-root');
        $roleService = static::getContainer()->get(RolesService::class);
        static::assertInstanceOf(RolesService::class, $roleService);
        $resource = static::getContainer()->get(UserResource::class);
        static::assertInstanceOf(UserResource::class, $resource);
        $userEntity = $resource->findOneBy([
            'username' => 'john-root',
        ]);
        self::assertInstanceOf(User::class, $userEntity);

        $client->request(method: 'GET', uri: $this->baseUrl . '/roles');
        $response = $client->getResponse();
        $content = $response->getContent();
        self::assertNotFalse($content);
        self::assertSame(Response::HTTP_OK, $response->getStatusCode(), "Response:\n" . $response);
        $responseData = JSON::decode($content, true);
        self::assertIsArray($responseData);
        self::assertIsString($responseData[0]);
        self::assertCount(count($roleService->getInheritedRoles($userEntity->getRoles())), $responseData);
    }

    /**
     * @testdox Test that `GET /api/v1/profile/roles` for non-logged user returns error response.
     *
     * @throws Throwable
     */
    public function testThatGetGetUserRolesActionForNonLoggedUserReturnsErrorResponse(): void
    {
        $client = $this->getTestClient();

        $client->request(method: 'GET', uri: $this->baseUrl . '/roles');
        $response = $client->getResponse();
        $content = $response->getContent();
        self::assertNotFalse($content);
        self::assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode(), "Response:\n" . $response);
    }
}
