<?php

namespace App\Entity\Comment;

use App\Entity\Post\Post;
use App\Entity\User\User;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass:"App\Repository\CommentsRepository")]
#[Table(name: "`comments`")]
class Comment implements JsonSerializable
{
    #[Id]
    #[Column(name: "comment_uuid", type: "uuid", unique: true)]
    #[GeneratedValue(strategy: 'CUSTOM')]
    #[CustomIdGenerator(class: UuidGenerator::class)]
    private Uuid $comment_uuid;

    #[ManyToOne(targetEntity: Post::class, inversedBy: 'comments' )]
    #[JoinColumn(name: 'post_uuid', referencedColumnName: 'post_uuid')]
    private Post $post;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'comments')]
    #[JoinColumn(name: 'user_uuid', referencedColumnName: 'user_uuid')]
    private User $author;

    #[Column(name: 'comment_text', type: 'string')]
    private string $text;

    static public function create(string $text, User $author, Post $post): Comment
    {
        $comment = new self();
        $comment->text = $text;
        $comment->author = $author;
        $comment->post = $post;

        return $comment;
    }

    public function getCommentUuid(): ?Uuid
    {
        return $this->comment_uuid;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function jsonSerialize(): mixed
    {
        return [
            "commnt_uuid" => $this->getCommentUuid(),
            "post_uuid" => $this->getPost()->getPostUuid(),
            "user_uuid" => $this->getAuthor()->getUser_uuid(),
            "comment" => $this->getText()
        ];
    }
}