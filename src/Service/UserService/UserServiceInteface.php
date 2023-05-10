<?php

namespace App\Service\UserService;

use App\Entity\User\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

interface UserServiceInterface
{
    static public function handler(Request $request, ?UserPasswordHasherInterface $passwordHasher): User;
}
