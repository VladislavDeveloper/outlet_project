<?php

namespace App\Service\UserService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User\User;
use DateTime;

class UpdateUserService
{
    static public function handler(Request $request, User $user, UserPasswordHasherInterface $passwordHasher): User
    {
        if($request->get('username')){
            $user->setUsername($request->get('username'));
        }
        if($request->get('first_name')){
            $user->setFirst_name($request->get('first_name'));
        }
        if($request->get('last_name')){
            $user->setLast_name($request->get('last_name'));
        }
        if($request->get('user_photo_id')){
            $user->setUser_photo_id($request->get('user_photo_id'));
        }
        if($request->get('date_of_birth')){
            $date_of_birth = DateTime::createFromFormat('d/m/Y', $request->get('date_of_birth'));
            $user->setDate_of_birth($date_of_birth);
        }
        if($request->get('gender')){
            $user->setGender($request->get('gender'));
        }
        if($request->get('password')){
            $hash = $passwordHasher->hashPassword($user, $request->get('password'));
            $user->setPassword($hash);
        }

        return $user;
    }
}