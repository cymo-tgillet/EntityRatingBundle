<?php

namespace Cymo\Bundle\EntityRatingBundle\Controller;

use Blogtrotting\AdventureBundle\Manager\EntityRatingManager;
use Cymo\Bundle\EntityRatingBundle\Exception\EntityRateIpLimitationReachedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class EntityRatingController extends Controller
{
    public function rateEntityAction(Request $request, $type, $id)
    {
        $manager = $this->container->getParameter('cymo_entity_rating.entity_rating_manager_service');
        /** @var EntityRatingManager $ratingManager */
        $ratingManager = $this->container->get($manager);

        try {
            $form = $ratingManager->generateForm($type, $id, $request->request->get('form_name'));
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $ratingManager->rate(
                    $form->get('entityType')->getData(),
                    $form->get('entityId')->getData(),
                    $form->get('rate')->getData()
                );

                return new JsonResponse(
                    [
                        'success'        => true,
                        'formName'       => $form->getName(),
                        'entityRatingId' => 'entityrating-form-'.$form->get('entityType')->getData().'-'.$form->get('entityId')->getData(),
                        'rateData'       => $ratingManager->getGlobalRateData($form->get('entityId')->getData(), $form->get('entityType')->getData()),
                    ]
                );
            }
        } catch (EntityRateIpLimitationReachedException $e) {
            return new JsonResponse(['success' => false, 'errorMessage' => $e->getMessage()], 300);
        }

        return new JsonResponse(['success' => false, 'errorMessage' => 'An error occured. Sorry.'], 300);
    }
}