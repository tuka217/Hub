<?php

namespace Sylius\Hub\Provider;

use Packagist\Api\Client;
use Composer\Semver\Comparator;
use Packagist\Api\Result\Package\Version;

/**
 * @author Anna Walasek <anna.walasek@lakion.com>
 */
final class SyliusVersionProvider implements SyliusVersionProviderInterface
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function provide()
    {
        try {
            $package = $this->client->get('sylius/sylius');
        }
        catch (\RuntimeException $exception) {
            return null;
        }

        $versions = array_map(function (Version $version) { return $version->getVersion(); }, $package->getVersions());

        return array_reduce(
            $versions,
            function ($accumulator, $version) {
                if (false !== strpos($version, 'dev')) {
                    return $accumulator;
                }

                if (null === $accumulator) {
                    return $version;
                }

                return Comparator::greaterThan($version, $accumulator) ? $version : $accumulator;
            });
    }
}
