<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hash;

    public function __construct(UserPasswordHasherInterface $hash)
    {
        $this->hash = $hash;
    }
    
    public function load(ObjectManager $manager): void
    {
        $users =[];
        for ($i=0; $i < 10; $i++) { 
            $user = new User();
            $user->setFirstName('User ' . $i)
            ->setLastName('User'. $i + 3)
            ->setEmail('user'. $i. '@gmail.com')
            ->setPhoneNumber(mt_rand(0,9)*10)
            ->setRoles(['ROLE_USER']);

            $hashPassword = $this->hash->hashPassword(
                $user,
                'password'
            );

            $user->setPassword($hashPassword);

            $users[] = $user;
            $manager->persist($user);
        }
        $manager->flush();
    }
}
