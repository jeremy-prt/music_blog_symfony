<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@blog.test');
        $admin->setPseudo('JeremyAdmin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'adminpass')
        );
        $manager->persist($admin);

        $user = new User();
        $user->setEmail('user@blog.test');
        $user->setPseudo('JeremyUtilisateur');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, 'userpass')
        );
        $manager->persist($user);

        $manager->flush();
    }
}