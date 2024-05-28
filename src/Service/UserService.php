<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    private UserPasswordHasherInterface $passwordHasher;

        public function __construct(UserPasswordHasherInterface $passwordHasher)
            {
                 $this->passwordHasher = $passwordHasher;
            }   

        public function encodePassword(User $user, string $plainPassword): string
            {
                return $this->passwordHasher->hashPassword($user, $plainPassword);
            }   
}