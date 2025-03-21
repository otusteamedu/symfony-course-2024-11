<?php

namespace App\Domain\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'subscription')]
#[ORM\Entity]
#[ORM\Index(name: 'subscription__author_id__ind', columns: ['author_id'])]
#[ORM\Index(name: 'subscription__follower_id__ind', columns: ['follower_id'])]
#[ApiResource]
#[ApiFilter(SearchFilter::class, properties: ['follower.login' => 'partial'])]
#[ORM\HasLifecycleCallbacks]
class Subscription implements EntityInterface
{
    #[ORM\Column(name: 'id', type: 'bigint', unique: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: 'User', inversedBy: 'subscriptionFollowers')]
    #[ORM\JoinColumn(name: 'author_id', referencedColumnName: 'id')]
    private User $author;

    #[ORM\ManyToOne(targetEntity: 'User', inversedBy: 'subscriptionAuthors')]
    #[ORM\JoinColumn(name: 'follower_id', referencedColumnName: 'id')]
    private User $follower;

    #[ORM\Column(name: 'created_at', type: 'datetime', nullable: false)]
    private DateTime $createdAt;

    #[ORM\Column(name: 'updated_at', type: 'datetime', nullable: false)]
    private DateTime $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): void
    {
        $this->author = $author;
    }

    public function getFollower(): User
    {
        return $this->follower;
    }

    public function setFollower(User $follower): void
    {
        $this->follower = $follower;
    }

    public function getCreatedAt(): DateTime {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void {
        $this->createdAt = new DateTime();
    }

    public function getUpdatedAt(): DateTime {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAt(): void {
        $this->updatedAt = new DateTime();
    }
}
