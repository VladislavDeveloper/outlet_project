<?php

namespace App\tests\Unit\Controller;

use App\Service\MyProfile;
use App\Service\UserService\CreateUserService;
use Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use function PHPUnit\Framework\assertEquals;

class UserControllerTest extends TestCase
{
    public function testCreateUserServiceReturnUserObject(): void
    {
           
    }

}