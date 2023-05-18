<?php

namespace App\Entity\User;

use App\Entity\Comment\Comment;
use App\Entity\Dislike\Dislike;
use App\Entity\Like\Like;
use App\Entity\Post\Post;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass:"App\Repository\UsersRepository\UserRepository")]
#[Table(name: "`users`")]
class User implements JsonSerializable, UserInterface, PasswordAuthenticatedUserInterface
{
    #[Id]
    #[Column(name: "user_uuid", type: "uuid", unique: true)]
    #[GeneratedValue(strategy: 'CUSTOM')]
    #[CustomIdGenerator(class: UuidGenerator::class)]
    private Uuid $user_uuid;

    #[Column(type: "string")]
    private string $username;

    #[Column(type: "string", length: 150)]
    private string $password;

    #[Column(type: "string")]
    private string $first_name;

    #[Column(type: "string")]
    private string $last_name;

    #[Column(type: "string")]
    private string $user_photo_id;

    #[Column(name: 'date_of_birth', type: Types::DATETIME_MUTABLE)]
    private DateTime $date_of_birth;

    #[Column(name: 'date_of_create', type: Types::DATETIME_MUTABLE)]
    private DateTime $date_of_create;

    #[Column(type: "string", length: 6)]
    private string $gender;

    #[Column(name: 'interests')]
    private array $interestes = [];

    #[Column(name: 'roles')]
    private array $roles;

    #[OneToMany(targetEntity: 'App\Entity\Post\Post', mappedBy: 'author')]
    private $posts;

    #[OneToMany(targetEntity: Comment::class, mappedBy: 'author')]
    private $comments;

    #[OneToMany(targetEntity: Like::class, mappedBy: 'author')]
    private $likes;

    #[OneToMany(targetEntity: Dislike::class, mappedBy: 'author')]
    private $dislikes;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->dislikes = new ArrayCollection();
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    /**
     * Get the value of user_uuid
     */ 
    public function getUser_uuid(): Uuid
    {
        return $this->user_uuid;
    }

    /**
     * Set the value of user_uuid
     */ 
    public function setUser_uuid($user_uuid): void
    {
        $this->user_uuid = $user_uuid;
    }

    /**
     * Get the value of username
     */ 
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set the value of username
     */ 
    public function setUsername($username): void
    {
        $this->username = $username;

    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    

    /**
     * Get the value of first_name
     */ 
    public function getFirst_name(): string
    {
        return $this->first_name;
    }

    /**
     * Set the value of first_name
     */ 
    public function setFirst_name($first_name): void
    {
        $this->first_name = $first_name;
    }

    /**
     * Get the value of last_name
     */ 
    public function getLast_name(): string
    {
        return $this->last_name;
    }

    /**
     * Set the value of last_name
     */ 
    public function setLast_name($last_name): void
    {
        $this->last_name = $last_name;
    }

    /**
     * Get the value of user_photo_id
     */ 
    public function getUser_photo_id(): string
    {
        return $this->user_photo_id;
    }

    /**
     * Set the value of user_photo_id
     */ 
    public function setUser_photo_id($user_photo_id): void
    {
        $this->user_photo_id = $user_photo_id;
    }

    /**
     * Get the value of date_of_birth
     */ 
    public function getDate_of_birth()
    {
        return $this->date_of_birth;
    }

    /**
     * Set the value of date_of_birth
     */ 
    public function setDate_of_birth(DateTime $date_of_birth): void
    {

        $this->date_of_birth = $date_of_birth;

    }

    /**
     * Get the value of date_of_create
     */ 
    public function getDate_of_create()
    {
        return $this->date_of_create;
    }

    /**
     * Set the value of date_of_create
     */ 
    public function setDate_of_create(DateTime $date_of_create): void
    {
        $this->date_of_create = $date_of_create;

    }

    /**
     * Get the value of gender
     */ 
    public function getGender(): string
    {
        return $this->gender;
    }

    /**
     * Set the value of gender
     */ 
    public function setGender($gender): void
    {
        $this->gender = $gender;

    }

    /**
     * Get the value of interestes
     */ 
    public function getInterestes(): array
    {
        return $this->interestes;
    }

    /**
     * Set the value of interestes
     */ 
    public function setInterestes($interestes): void
    {
        $this->interestes = $interestes;

    }

    public function jsonSerialize(): mixed
    {
        return[
            "user_uuid" => $this->getUser_uuid(),
            "username" => $this->getUsername(),
            "first_name" => $this->getFirst_name(),
            "last_name" => $this->getLast_name(),
            "gender" => $this->getGender(),
            "date_of_birth" => $this->getDate_of_birth(),
            "date_of_cerate" => $this->getDate_of_create(),
            "interests" => $this->getInterestes(),
            "roles" => $this->getRoles()
        ];
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUserUuid(): ?Uuid
    {
        return $this->user_uuid;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getUserPhotoId(): ?string
    {
        return $this->user_photo_id;
    }

    public function setUserPhotoId(string $user_photo_id): self
    {
        $this->user_photo_id = $user_photo_id;

        return $this;
    }

    public function getDateOfBirth()
    {
        return $this->date_of_birth;
    }

    public function setDateOfBirth(\DateTimeInterface $date_of_birth): self
    {
        $this->date_of_birth = $date_of_birth;

        return $this;
    }

    public function getDateOfCreate()
    {
        return $this->date_of_create;
    }

    public function setDateOfCreate(\DateTimeInterface $date_of_create): self
    {
        $this->date_of_create = $date_of_create;

        return $this;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setAuthor($this);
        }

        return $this;
    }

    public function removeLike(Like $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getAuthor() === $this) {
                $like->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Dislike>
     */
    public function getDislikes(): Collection
    {
        return $this->dislikes;
    }

    public function addDislike(Dislike $dislike): self
    {
        if (!$this->dislikes->contains($dislike)) {
            $this->dislikes->add($dislike);
            $dislike->setAuthor($this);
        }

        return $this;
    }

    public function removeDislike(Dislike $dislike): self
    {
        if ($this->dislikes->removeElement($dislike)) {
            // set the owning side to null (unless already changed)
            if ($dislike->getAuthor() === $this) {
                $dislike->setAuthor(null);
            }
        }

        return $this;
    }
}