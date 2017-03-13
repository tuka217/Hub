<?php

namespace Tests\Sylius\Hub;

use Lakion\ApiTestCase\JsonApiTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Anna Walasek <anna.walasek@lakion.com>
 */
class SyliusVersionApiTest extends JsonApiTestCase
{
    /**
     * @var array
     */
    private static $HeaderWithAccept = [
        'ACCEPT' => 'application/json',
    ];

    /**
     * @test
     */
    public function it_allows_to_get_sylius_current_version()
    {
        $this->client->request('GET', '/sylius/version');

        $response = $this->client->getResponse();
        $this->assertResponse($response, 'version/sylius_version_response', Response::HTTP_OK);
    }
}
