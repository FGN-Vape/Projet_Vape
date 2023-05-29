<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Specificities;
use App\Entity\Type;
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
        //Users
        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setFirstName('User ' . $i)
                ->setLastName('User' . $i + 3)
                ->setEmail('user' . $i . '@gmail.com')
                ->setPhoneNumber(mt_rand(0, 9) * 10)
                ->setRoles(['ROLE_USER']);

            $hashPassword = $this->hash->hashPassword(
                $user,
                'password'
            );

            $user->setPassword($hashPassword);

            $users[] = $user;
            $manager->persist($user);
        }
            //Types
            $type1 = new Type();
            $type2 = new Type();
            $type3 = new Type();
            $type1->setNameType('Cigarette');
            $type2->setNameType('Resistance');
            $type3->setNameType('E-Liquide');
            $manager->persist($type1);
            $manager->persist($type2);
            $manager->persist($type3);

            //Trouver le type avec l'ID
            $typeCigarette = $manager->getRepository(Type::class)->findOneBy(['NameType' => 'Cigarette']);
            $typeResistance = $manager->getRepository(Type::class)->findOneBy(['NameType' => 'Resistance']);
            $typeLiquide = $manager->getRepository(Type::class)->findOneBy(['NameType' => 'E-liquide']);
            
            // cigarette 
            $cigarette = new Product();
            $cigarette->setName('Vaporesso Deluxe Edition 100W');
            $cigarette->setBrand('Vaporesso');
            $cigarette->setPrice(100);
            $cigarette->setImg('/le/chemin/de/limage');
            $cigarette->setType($typeCigarette);
            $manager->persist($cigarette);

            // resistance 
            $resistance = new Product();
            $resistance->setName('Eleaf 0.5 Ohm x6');
            $resistance->setBrand('Eleaf');
            $resistance->setPrice(12.5);
            $resistance->setImg('/le/chemin/de/limage');
            $resistance->setType($typeResistance);
            $manager->persist($resistance);

            // liquide 
            $liquide = new Product();
            $liquide->setName('Saveur Orange par PureVapor en 6mg/ml de nicotine');
            $liquide->setBrand('PureVapor');
            $liquide->setPrice(6);
            $liquide->setImg('/le/chemin/de/limage');
            $liquide->setType($typeLiquide);
            $manager->persist($liquide);

        $manager->flush();
    }
}
