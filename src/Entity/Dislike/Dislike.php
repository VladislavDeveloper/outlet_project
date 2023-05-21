<?php

namespace App\Entity\Dislike;

use App\Entity\Post\Post;
use App\Entity\User\User;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass:"App\Repository\DislikeRepository\DislikeRepository")]
#[Table(name: "`dislikes`")]
class Dislike implements JsonSerializable
{
    #[Id]
    #[Column(name: "like_uuid", type: "uuid", unique: true)]
    #[GeneratedValue(strategy: 'CUSTOM')]
    #[CustomIdGenerator(class: UuidGenerator::class)]
    private Uuid $dislike_uuid;

    #[OneToOne(targetEntity: Post::class, inversedBy: 'dislikes')]
    #[JoinColumn(name: 'post_uuid', referencedColumnName: 'post_uuid')]
    private Post $post;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'dislikes')]
    #[JoinColumn(name: 'user_uuid', referencedColumnName: 'user_uuid')]
    private User $author;

    

    /**
     * Get the value of like_uuid
     */ 
    public function getDislike_uuid()
    {
        return $this->dislike_uuid;
    }

    /**
     * Set the value of like_uuid
     *
     * @return  self
     */ 
    public function setLike_uuid($dislike_uuid)
    {
        $this->dislike_uuid = $dislike_uuid;

        return $this;
    }

    /**
     * Get the value of post
     */ 
    public function getPost()
    {
        return $this->post;
    }

    /**
     * Set the value of post
     *
     * @return  self
     */ 
    public function setPost($post)
    {
        $this->post = $post;

        return $this;
    }

    /**
     * Get the value of author
     */ 
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set the value of author
     *
     * @return  self
     */ 
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return
        [
            'dislike_uuid' => $this->getDislike_uuid(),
            'post' => $this->getPost()->getPostUuid(),
            'author' => $this->getAuthor()->getUser_uuid(),
        ];
    }

    public function getDislikeUuid(): ?Uuid
    {
        return $this->dislike_uuid;
    }
}