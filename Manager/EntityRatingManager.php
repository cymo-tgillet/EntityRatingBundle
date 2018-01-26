<?php

namespace Cymo\Bundle\EntityRatingBundle\Manager;

use Cymo\Bundle\EntityRatingBundle\Annotation\Rated;
use Cymo\Bundle\EntityRatingBundle\Entity\EntityRate;
use Cymo\Bundle\EntityRatingBundle\Exception\UnsupportedEntityRatingClass;
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

        $this->addRate(22, 'step', 3);
    }

    public function createOrUpdateRate($entityId, $entityType, $vote)
    {

        $this->container->getParameter('cymo_entity_rating.rate_by_ip_limitation');

        $this->entityRateRepository->findBy(
            [
                'id'           => $entityId,
                'targetEntity' => $entityType,
                'ip'           => $this->userIp,
            ]
        );
    }

    public function removeRate()
    {
        
    }

    public function addRate($entityId, $entityType, $rateValue)
    {
        $rate = new EntityRate();
        $rate->setEntityType($entityType);
        $rate->setIp($this->userIp);
        $rate->setEntityId($entityId);
        $rate->setUserAgent($this->userAgent);
        $rate->setRate($rateValue);
        $rate->setCreatedAt(new \DateTime());

        $this->entityManager->persist($rate);
        $this->entityManager->flush();
    }

    public function generateForm($class)
    {
        if ($annotation = $this->typeIsSupported($class)) {
            return $this->formFactory->getForm($class, $annotation);
        }
        throw new UnsupportedEntityRatingClass(sprintf('Class does not support EntityRating, you must add the `Rated` annotation to the %s class first', $class));
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
}