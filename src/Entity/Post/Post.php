<?php

namespace App\Entity\Post;

use App\Entity\User\User;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

#[Entity(repositoryClass:"App\PostsRepository\PostRepository")]
#[Table(name: "`posts`")]
class Post implements JsonSerializable
{

    #[Id]
    #[Column(name: "post_uuid", type: "uuid", unique: true)]
    #[GeneratedValue(strategy: 'CUSTOM')]
    #[CustomIdGenerator(class: UuidGenerator::class)]
    private Uuid $post_uuid;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'posts' )]
    #[JoinColumn(name: 'user_uuid', referencedColumnName: 'user_uuid')]
    private User $author;

    #[Column(type: 'string')]
    private string $title;

    #[Column(type: 'string')]
    private string $body;

    #[Column(type: 'string')]
    private string $post_image_id;

    #[Column(name: 'date_of_create', type: Types::DATETIME_MUTABLE)]
    private DateTime $date_of_create;

    #[Column(name: 'comments')]
    private array $comments;

    #[Column(name: 'likes')]
    private array $likes;

    #[Column(name: 'dislikes')]
    private array $dislikes;


    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $user): self
    {
        $this->author = $user;

        return $this;
    }

    public function getPostUuid(): ?Uuid
    {
        return $this->post_uuid;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    public function getPostImageId(): ?string
    {
        return $this->post_image_id;
    }

    public function setPostImageId(string $post_image_id): self
    {
        $this->post_image_id = $post_image_id;

        return $this;
    }

    public function getDateOfCreate(): ?\DateTimeInterface
    {
        return $this->date_of_create;
    }

    public function setDateOfCreate(\DateTimeInterface $date_of_create): self
    {
        $this->date_of_create = $date_of_create;

        return $this;
    }

    public function getComments(): array
    {
        return $this->comments;
    }

    public function setComments(array $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    public function getLikes(): array
    {
        return $this->likes;
    }

    public function setLikes(array $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getDislikes(): array
    {
        return $this->dislikes;
    }

    public function setDislikes(array $dislikes): self
    {
        $this->dislikes = $dislikes;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return[
            "post_uuid" => $this->getPostUuid(),
            "title" => $this->getTitle(),
            "body" => $this->getBody(),
            "post_image_id" => $this->getPostImageId(),
            "author" => $this->getAuthor(),
            "date_of_create" => $this->getDateOfCreate(),
            "comments" => $this->getComments(),
            "likes" => $this->getLikes(),
            "dislikes" => $this->getDislikes()
        ];
    }

}