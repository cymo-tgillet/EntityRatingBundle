<?php
/**
 * Created by PhpStorm.
 * User: cymo
 * Date: 25/01/18
 * Time: 16:28
 */

namespace Cymo\Bundle\EntityRatingBundle\Controller;

use Blogtrotting\AdventureBundle\Entity\Itinerary\Itinerary;
use Cymo\Bundle\EntityRatingBundle\Entity\EntityRate;
use Cymo\Bundle\EntityRatingBundle\Exception\EntityRateIpLimitationReachedException;
use Cymo\Bundle\EntityRatingBundle\Form\RatingType;
use Cymo\Bundle\EntityRatingBundle\Manager\EntityRatingManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EntityRatingController extends Controller
{
    public function rateEntityAction(Request $request, $type, $id)
    {
        /** @var EntityRatingManager $ratingManager */
        $ratingManager = $this->container->get('cymo.entity_rating_bundle.manager');

        try {
            $form = $this->get('cymo.entity_rating_bundle.manager')->generateForm(Itinerary::class, $type, $id);
            $form->handleRequest($request);
            if ($form->isValid() && $form->isSubmitted()) {
                $ratingManager->rate(
                    $form->get('entityType')->getData(),
                    $form->get('entityId')->getData(),
                    $form->get('rate')->getData()
                );
            }
        } catch (EntityRateIpLimitationReachedException $e) {
            return new JsonResponse(['success' => false, 'errorMessage' => $e->getMessage()], 300);
        }

        return new JsonResponse(['success' => true]);
    }
}