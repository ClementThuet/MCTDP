<?php


namespace App\DataFixtures;
 
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
 
class DataUserFixtures extends Fixture
{
    private $passwordEncoder;
 
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
 
    public function load(ObjectManager $manager)
    {

        $user = new User();
        $user->setFullName('Jane Doe');
        $user->setUsername('jane_admin');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'toto'));
        $user->setEmail('jane_admin@symfony.com');
        $user->setRoles(['ROLE_USER']);
        $username='jane_admin';
        $manager->persist($user);
        //$this->addReference($username, $user);
        $user2 = new User();
        $user2->setFullName('Clément Thuet');
        $user2->setUsername('clement');
        $user2->setPassword($this->passwordEncoder->encodePassword($user, 'toto'));
        $user2->setEmail('clement@symfony.com');
        $user2->setRoles(['ROLE_USER']);
        //$username='jane_admin';
        $manager->persist($user2);
 
        $manager->flush();
    }
 
    private function getUserData(): array
    {
        return [
            // $userData = [$fullname, $username, $password, $email, $roles];
            ['Jane Doe', 'jane_admin', 'kitten', 'jane_admin@symfony.com', ['ROLE_ADMIN']],
            ['Tom Doe', 'tom_admin', 'kitten', 'tom_admin@symfony.com', ['ROLE_ADMIN']],
            ['John Doe', 'john_user', 'kitten', 'john_user@symfony.com', ['ROLE_USER']],
        ];
    }
 
 
}

