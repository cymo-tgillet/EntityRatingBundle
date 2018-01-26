<?php

namespace Cymo\Bundle\EntityRatingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EntityRatingRate
 * @ORM\Table(name="entity_rate")
 * @ORM\Entity(repositoryClass="Cymo\Bundle\EntityRatingBundle\Repository\EntityRateRepository")
 */
class EntityRate
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     * @ORM\Column(name="rate", type="float")
     */
    private $rate;

    /**
     * @var int
     * @ORM\Column(name="entity_id", type="integer")
     */
    private $entityId;

    /**
     * @var string
     * @ORM\Column(name="entity_type", type="string", length=255)
     */
    private $entityType;

    /**
     * @var string
     * @ORM\Column(name="ip", type="string", length=255)
     */
    private $ip;

    /**
     * @var string
     * @ORM\Column(name="user_agent", type="string", length=255)
     */
    private $userAgent;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getRate(): float
    {
        return $this->rate;
    }

    /**
     * @param float $rate
     */
    public function setRate(float $rate)
    {
        $this->rate = $rate;
    }

    /**
     * @return string
     */
    public function getEntityType(): string
    {
        return $this->entityType;
    }

    /**
     * @param string $entityType
     */
    public function setEntityType(string $entityType)
    {
        $this->entityType = $entityType;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     */
    public function setIp(string $ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     */
    public function setUserAgent(string $userAgent)
    {
        $this->userAgent = $userAgent;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return int
     */
    public function getEntityId(): int
    {
        return $this->entityId;
    }

    /**
     * @param int $entityId
     */
    public function setEntityId(int $entityId)
    {
        $this->entityId = $entityId;
    }

}
