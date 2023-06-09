<?php

namespace App\Service\UserService;

use App\Entity\User\User;
use DateTime;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CreateUserService
{
    static public function handler(Request $request, ?UserPasswordHasherInterface $passwordHasher): User
    {
        try {

            $uploadFile = $request->files->get('user_image');

            $uploadFile = $uploadFile ?? null;

            $user = new User();

            $user->setUsername($request->get('username'));

            $user->setFirst_name($request->get('first_name'));

            $user->setLast_name($request->get('last_name'));

            $user->setProfileImage($uploadFile);

            $date_of_birth = DateTime::createFromFormat('d/m/Y', $request->get('date_of_birth'));

            $user->setDate_of_birth($date_of_birth);

            $date_of_create = new DateTime();

            $user->setDate_of_create($date_of_create);

            $user->setGender($request->get('gender'));

            $user->setRoles(['ROLE_USER']);

            $hash = $passwordHasher->hashPassword($user, $request->get('password'));

            $user->setPassword($hash);

            return $user;

        } catch (Exception $e) {
            Throw new Exception('wrong data');
        }

        return new $user;
    }
}