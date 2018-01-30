<?php

namespace Cymo\Bundle\EntityRatingBundle\Manager;

use Cymo\Bundle\EntityRatingBundle\Annotation\Rated;
use Cymo\Bundle\EntityRatingBundle\Entity\EntityRate;
use Cymo\Bundle\EntityRatingBundle\Exception\EntityRateIpLimitationReachedException;
use Cymo\Bundle\EntityRatingBundle\Exception\UndeclaredEntityRatingTypeException;
use Cymo\Bundle\EntityRatingBundle\Exception\UnsupportedEntityRatingClassException;
use Cymo\Bundle\EntityRatingBundle\Factory\EntityRatingFormFactory;
use Cymo\Bundle\EntityRatingBundle\Repository\EntityRateRepository;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\DependencyInjection\Container;

class EntityRatingManager
{
    /**
     * @var AnnotationReader
     */
    private $annotationReader;
    /**
     * @var EntityRatingFormFactory
     */
    private $formFactory;
    /**
     * @var EntityRateRepository
     */
    private $entityRateRepository;
    /**
     * @var Container
     */
    private $container;

    public function __construct(
        AnnotationReader $annotationReader,
        EntityRatingFormFactory $formFactory,
        EntityRateRepository $entityRateRepository,
        Container $container
    ) {
        $this->annotationReader     = $annotationReader;
        $this->formFactory          = $formFactory;
        $this->entityRateRepository = $entityRateRepository;
        $this->container            = $container;
        $this->userIp               = $_SERVER['REMOTE_ADDR'];
        $this->userAgent            = $_SERVER['HTTP_USER_AGENT'] ?? null;
        $this->entityManager        = $container->get('doctrine.orm.entity_manager');
    }

    public function rate($entityType, $entityId, $rateValue)
    {
        $configTypes = $this->container->getParameter('cymo_entity_rating.map_type_to_class');

        if (false === array_key_exists($entityType, $configTypes)) {
            throw new UndeclaredEntityRatingTypeException(sprintf('You must declare the %s type and the corresponding class under the cymo_entity_rating.map_type_to_class configuration key.', $entityType));
        }

        if (false === $this->typeIsSupported($configTypes[$entityType])) {
            throw new UnsupportedEntityRatingClassException(sprintf('Class does not support EntityRating, you must add the `Rated` annotation to the %s class first', $configTypes[$entityType]));
        }

        /** @var EntityRate $rate */
        $rate = $this->entityRateRepository->findOneBy(
            [
                'entityId'   => $entityId,
                'entityType' => $entityType,
                'ip'         => $this->userIp,
                'userAgent'  => $this->userAgent,
            ]
        );

        if ($rate) {
            $this->updateRate($rate, $rateValue);
        } else {
            if ($this->allowRatingEntity($entityId, $entityType)) {
                $this->addRate($entityId, $entityType, $rateValue);
            } else {
                throw new EntityRateIpLimitationReachedException('Rating quota reached for this IP address and object.');
            }
        }
    }

    private function updateRate(EntityRate $rate, $rateValue)
    {
        $rate->setRate($rateValue);
        $rate->setUpdatedAt(new \DateTime());
        $this->entityManager->flush();
    }

    public function addRate($entityId, $entityType, $rateValue)
    {
        $rate = new EntityRate();
        $rate->setRate($rateValue);
        $rate->setEntityId($entityId);
        $rate->setEntityType($entityType);
        $rate->setIp($this->userIp);
        $rate->setUserAgent($this->userAgent);
        $rate->setCreatedAt(new \DateTime());

        $this->entityManager->persist($rate);
        $this->entityManager->flush();
    }

    public function generateForm($class, $entityType, $entityId)
    {
        if ($annotation = $this->typeIsSupported($class)) {
            return $this->formFactory->getForm($annotation, $entityType, $entityId);
        }
        throw new UnsupportedEntityRatingClassException(sprintf('Class does not support EntityRating, you must add the `Rated` annotation to the %s class first', $class));
    }

    /**
     * @param $entityClass
     *
     * @return bool|Rated
     */
    protected function typeIsSupported($entityClass)
    {
        $reflClass        = new \ReflectionClass($entityClass);
        $classAnnotations = $this->annotationReader->getClassAnnotations($reflClass);

        foreach ($classAnnotations AS $annot) {
            if ($annot instanceof Rated) {
                return $annot;
            }
        }

        return false;
    }

    private function allowRatingEntity($entityId, $entityType)
    {
        $rateByIpLimitation = $this->container->getParameter('cymo_entity_rating.rate_by_ip_limitation');

        if ($rateByIpLimitation == 0) {
            return true;
        }

        $rates = $this->entityRateRepository->findBy(
            [
                'entityId'   => $entityId,
                'entityType' => $entityType,
                'ip'         => $this->userIp,
            ]
        );

        return count($rates) < $this->container->getParameter('cymo_entity_rating.rate_by_ip_limitation');
    }

}