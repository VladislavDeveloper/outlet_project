<?php

namespace App\Entity\Post;

use App\Entity\Comment\Comment;
use App\Entity\Dislike\Dislike;
use App\Entity\Like\Like;
use App\Entity\User\User;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;
use Symfony\Component\Uid\Uuid;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Entity(repositoryClass:"App\Repository\PostsRepository\PostRepository")]
#[Table(name: "`posts`")]
#[Vich\Uploadable]
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

    #[Column(type: 'string', nullable: true)]
    private ?string $imageName = null;

    #[Vich\UploadableField(mapping: 'posts', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[Column(name: 'date_of_create', type: Types::DATETIME_MUTABLE)]
    private DateTime $date_of_create;

    #[OneToMany(targetEntity: Comment::class, mappedBy: 'post', cascade: ['remove'])]
    private $comments;

    #[OneToMany(targetEntity: Like::class, mappedBy: 'post', cascade: ['remove'])]
    private $likes;

    #[OneToMany(targetEntity: Dislike::class, mappedBy: 'post', cascade: ['remove'])]
    private $dislikes;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->dislikes = new ArrayCollection();
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
        }
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function addComment(Comment $comment): void
    {
         $comment->setPost($this);

         if (!$this->comments->contains($comment)) {
              $this->comments->add($comment);
          }
    }

    public function removeComment(Comment $comment): void
    {
        $this->comments->removeElement($comment);
    }

    public function addlike(Like $like): void
    {
        $like->setPost($this);

        if(!$this->likes->contains($like)){
            $this->likes->add($like);
        }
    }

    public function removeLike(Like $like): void
    {
        $this->likes->removeElement($like);
    }

    public function addDislike(Dislike $dislike): void
    {
        $dislike->setPost($this);

        if(!$this->dislikes->contains($dislike)){
            $this->dislikes->add($dislike);
        }
    }

    public function removeDislike(Dislike $dislike): void
    {
        $this->dislikes->removeElement($dislike);
    }

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

    public function getDateOfCreate(): ?\DateTimeInterface
    {
        return $this->date_of_create;
    }

    public function setDateOfCreate(\DateTimeInterface $date_of_create): self
    {
        $this->date_of_create = $date_of_create;

        return $this;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function setLikes(array $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getDislikes(): Collection
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
            "author" => $this->getAuthor(),
            "post_image" => $this->getImageName(),
            "date_of_create" => $this->getDateOfCreate(),
            "comments" => $this->getComments()->toArray(),
            "likes" => $this->getLikes()->toArray(),
            "dislikes" => $this->getDislikes()->toArray()
        ];
    }
}