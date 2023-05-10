<?php

namespace App\Entity\Post;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\CustomIdGenerator;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use JsonSerializable;
use Symfony\Component\Uid\Uuid;

#[Entity(repositoryClass:"App\PostsRepository\PostRepository")]
#[Table(name: "`posts`")]
class Post{

    #[Id]
    #[Column(name: "post_uuid", type: "uuid", unique: true)]
    #[GeneratedValue(strategy: 'CUSTOM')]
    #[CustomIdGenerator(class: UuidGenerator::class)]
    private Uuid $post_uuid;

    

}