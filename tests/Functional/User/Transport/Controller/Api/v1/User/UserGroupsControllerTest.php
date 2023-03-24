<?php

declare(strict_types=1);

namespace App\Tests\Functional\User\Transport\Controller\Api\v1\User;

use App\General\Domain\Utils\JSON;
use App\General\Transport\Utils\Tests\WebTestCase;
use App\Role\Domain\Enum\Role;
use App\User\Application\Resource\UserResource;
use App\User\Domain\Entity\User;
use Generator;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class UserGroupsControllerTest
 *
 * @package App\Tests
 */
class UserGroupsControllerTest extends WebTestCase
{
    private string $baseUrl = self::API_URL_PREFIX . '/v1/user';
    private User $userEntity;

    /**
     * @throws Throwable
     */
    protected function setUp(): void
    {
        parent::setUp();

        $userResource = static::getContainer()->get(UserResource::class);
        static::assertInstanceOf(UserResource::class, $userResource);
        $userEntity = $userResource->findOneBy([
            'username' => 'john-user',
        ]);
        self::assertInstanceOf(User::class, $userEntity);
        $this->userEntity = $userEntity;
    }

    /**
     * @testdox Test that `GET /api/v1/user/{$userId}/groups` request returns `401` for non-logged user.
     *
     * @throws Throwable
     */
    public function testThatGetUserGroupsForNonLoggedUserReturns401(): void
    {
        $client = $this->getTestClient();

        $client->request('GET', $this->baseUrl . '/' . $this->userEntity->getId() . '/groups');
        $response = $client->getResponse();
        $content = $response->getContent();
        self::assertNotFalse($content);
        self::assertSame(Response::HTTP_UNAUTHORIZED, $response->getStatusCode(), "Response:\n" . $response);
    }

    /**
     * @testdox Test that `GET /api/v1/user/{$userId}/groups` returns forbidden error for non-root/non-himself users.
     *
     * @throws Throwable
     */
    public function testThatGetUserGroupsForbiddenForNonRootAndNonHimselfUsers(): void
    {
        $client = $this->getTestClient('john-admin', 'password-admin');

        $client->request('GET', $this->baseUrl . '/' . $this->userEntity->getId() . '/groups');
        $response = $client->getResponse();
        $content = $response->getContent();
        self::assertNotFalse($content);
        self::assertSame(Response::HTTP_FORBIDDEN, $response->getStatusCode(), "Response:\n" . $response);
    }

    /**
     * @testdox Test that `GET /api/v1/user/{$userId}/groups` success under root/himself users.
     *
     * @dataProvider dataProviderTestThatGetUserGroupsWorksAsExpected
     *
     * @throws Throwable
     */
    public function testThatGetUserGroupsWorksAsExpected(string $login, string $password): void
    {
        $client = $this->getTestClient($login, $password);

        $client->request('GET', $this->baseUrl . '/' . $this->userEntity->getId() . '/groups');
        $response = $client->getResponse();
        $content = $response->getContent();
        self::assertNotFalse($content);
        self::assertSame(Response::HTTP_OK, $response->getStatusCode(), "Response:\n" . $response);
        $responseData = JSON::decode($content, true);
        self::assertIsArray($responseData);
        self::assertGreaterThan(0, $responseData);
        self::assertIsArray($responseData[0]);
        self::assertArrayHasKey('id', $responseData[0]);
        self::assertArrayHasKey('role', $responseData[0]);
        self::assertIsArray($responseData[0]['role']);
        self::assertArrayHasKey('id', $responseData[0]['role']);
        self::assertEquals(Role::USER->value, $responseData[0]['role']['id']);
        self::assertArrayHasKey('name', $responseData[0]);
    }

    /**
     * @return Generator<array{0: string, 1: string}>
     */
    public function dataProviderTestThatGetUserGroupsWorksAsExpected(): Generator
    {
        yield ['john-user', 'password-user'];
        yield ['john-root', 'password-root'];
    }
}
