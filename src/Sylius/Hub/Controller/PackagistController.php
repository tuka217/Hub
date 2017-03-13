<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Hub\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * @author Anna Walasek <anna.walasek@lakion.com>
 */
final class PackagistController extends Controller
{
    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws HttpException
     */
    public function getCurrentSyliusVersionAction(Request $request)
    {
        $currentSyliusVersion = $this->get('hub.cached_sylius_version_provider')->provide();

        if (!$currentSyliusVersion) {
            throw new HttpException(Response::HTTP_SERVICE_UNAVAILABLE, 'Packagist service is not responding!');
        }

        return new JsonResponse(['version' => $currentSyliusVersion]);
    }
}
