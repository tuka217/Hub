<?php

namespace spec\Sylius\Hub\Provider;

use Guzzle\Http\Exception\CurlException;
use Packagist\Api\Client;
use Packagist\Api\Result\Package;
use Packagist\Api\Result\Package\Version;
use Sylius\Hub\Provider\SyliusVersionProvider;
use PhpSpec\ObjectBehavior;
use Sylius\Hub\Provider\SyliusVersionProviderInterface;

/**
 * @author Anna Walasek <anna.walasek@lakion.com>
 */
class SyliusVersionProviderSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(SyliusVersionProvider::class);
    }

    function it_implements_sylius_version_resolver_interface()
    {
        $this->shouldImplement(SyliusVersionProviderInterface::class);
    }

    function it_returns_current_sylius_version(
        Client $client,
        Package $package,
        Version $firstVersion,
        Version $secondVersion,
        Version $thirdVersion
    ) {
        $client->get('sylius/sylius')->willReturn($package);
        $firstVersion->getVersion()->willReturn('dev-master');
        $secondVersion->getVersion()->willReturn('v1.0.0-beta.1');
        $thirdVersion->getVersion()->willReturn('v1.0.0-alpha.1');

        $versions = [
            $firstVersion->getWrappedObject(),
            $secondVersion->getWrappedObject(),
            $thirdVersion->getWrappedObject()
        ];

        $package->getVersions()->willReturn($versions);

        $this->provide()->shouldReturn('v1.0.0-beta.1');
    }

    function it_returns_nothing_if_packagist_service_is_not_responding(Client $client)
    {
        $client->get('sylius/sylius')->willThrow(new CurlException());

        $this->provide()->shouldReturn(null);
    }
}
