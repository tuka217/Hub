<?php

namespace Sylius\Hub\Provider;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

/**
 * @author Anna Walasek <anna.walasek@lakion.com>
 */
class CachedSyliusVersionProvider implements SyliusVersionProviderInterface
{
    /**
     * @var SyliusVersionProviderInterface
     */
    private $decoratedSyliusVersionProvider;

    /**
     * @var CacheItemPoolInterface
     */
    private $cache;

    /**
     * @param CacheItemPoolInterface $cache
     * @param SyliusVersionProviderInterface $decoratedSyliusVersionProvider
     */
    public function __construct(CacheItemPoolInterface $cache, SyliusVersionProviderInterface $decoratedSyliusVersionProvider)
    {
        $this->cache = $cache;
        $this->decoratedSyliusVersionProvider = $decoratedSyliusVersionProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function provide()
    {
        $cachedSyliusVersion = $this->cache->getItem('hub.sylius_version');

        if (!$cachedSyliusVersion->isHit()) {
            $currentSyliusVersion = $this->decoratedSyliusVersionProvider->provide();

            if (!$currentSyliusVersion) {
                return null;
            }

            $this->saveVersionInCache($cachedSyliusVersion, $currentSyliusVersion);

            return $currentSyliusVersion;
        }

        return $cachedSyliusVersion->get('value');
    }

    /**
     * @param CacheItemInterface $cachedSyliusVersion
     * @param string $currentSyliusVersion
     */
    private function saveVersionInCache(CacheItemInterface $cachedSyliusVersion, $currentSyliusVersion)
    {
        $date = new \DateTime('+5 minutes');

        $cachedSyliusVersion->set($currentSyliusVersion);
        $cachedSyliusVersion->expiresAt(clone $date);

        $this->cache->save($cachedSyliusVersion);
    }
}
