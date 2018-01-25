<?php
/**
 * Created by PhpStorm.
 * User: cymo
 * Date: 25/01/18
 * Time: 16:28
 */

namespace Cymo\Bundle\EntityRatingBundle\Controller;

use Cymo\Bundle\EntityRatingBundle\Manager\EntityRatingManager;
use Symfony\Component\HttpFoundation\JsonResponse;

class EntityRatingController
{
    public function rateEntityAction($type)
    {
        /** @var EntityRatingManager $starRatingManager */
        $starRatingManager = $this->container->get('blogtrotting.star_rating.manager');

        return new JsonResponse(['success' => true]);
    }
}