<?php

namespace spec\Sylius\Hub\Provider;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Sylius\Hub\Provider\CachedSyliusVersionProvider;
use Sylius\Hub\Provider\SyliusVersionProviderInterface;

/**
 * @author Anna Walasek <anna.walasek@lakion.com>
 */
class CachedSyliusVersionProviderSpec extends ObjectBehavior
{
    function let(CacheItemPoolInterface $cache, SyliusVersionProviderInterface $decoratedSyliusVersionProvider)
    {
        $this->beConstructedWith($cache, $decoratedSyliusVersionProvider);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CachedSyliusVersionProvider::class);
    }

    function it_implements_sylius_version_provider_interface()
    {
        $this->shouldImplement(SyliusVersionProviderInterface::class);
    }

    function it_returns_current_sylius_version_if_it_is_not_in_cache(
        CacheItemPoolInterface $cache,
        SyliusVersionProviderInterface $decoratedSyliusVersionProvider,
        CacheItemInterface $cacheItem
    ) {
        $cache->getItem(Argument::any())->willReturn($cacheItem);
        $cacheItem->isHit()->willReturn(false);

        $cacheItem->set(Argument::any())->shouldBeCalled();
        $cacheItem->expiresAt(Argument::type(\DateTime::class))->shouldBeCalled();
        $cache->save(Argument::any())->shouldBeCalled();

        $decoratedSyliusVersionProvider->provide()->willReturn('v1.0.0-beta.1');

        $this->provide()->shouldReturn('v1.0.0-beta.1');
    }

    function it_returns_current_sylius_version_if_it_is_in_cache(
        CacheItemPoolInterface $cache,
        CacheItemInterface $cacheItem
    ) {
        $cache->getItem(Argument::any())->willReturn($cacheItem);
        $cacheItem->isHit()->willReturn(true);
        $cacheItem->get('value')->willReturn('v1.0.0-beta.1');

        $this->provide()->shouldReturn('v1.0.0-beta.1');
    }

    function it_returns_nothing_if_decorated_sylius_version_provider_returns_nothing(
        SyliusVersionProviderInterface $decoratedSyliusVersionProvider,
        CacheItemInterface $cacheItem,
        CacheItemPoolInterface $cache
    ) {
        $decoratedSyliusVersionProvider->provide()->willReturn(null);
        $cache->getItem(Argument::any())->willReturn($cacheItem);
        $cacheItem->isHit()->willReturn(false);

        $this->provide()->shouldReturn(null);
    }
}
