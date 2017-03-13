<?php

namespace Sylius\Hub\Provider;

/**
 * @author Anna Walasek <anna.walasek@lakion.com>
 */
interface SyliusVersionProviderInterface
{
    /**
     * @return string|null
     */
    public function provide();
}
