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
        $user->setFullName('David Pace');
        $user->setUsername('David');
        $user->setPassword($this->passwordEncoder->encodePassword($user, 'mtcdpgestion'));
        $user->setEmail('NC');
        $user->setRoles(['ROLE_USER']);
        $manager->persist($user);
        //$this->addReference($username, $user);
        
        $user2 = new User();
        $user2->setFullName('Clément Thuet');
        $user2->setUsername('clement');
        $user2->setPassword($this->passwordEncoder->encodePassword($user, 'admin'));
        $user2->setEmail('clementthuet7@gmail.com');
        $user2->setRoles(['ROLE_USER']);
        //$username='jane_admin';
        $manager->persist($user2);
 
        $manager->flush();
    }
 
    private function getUserData(): array
    {
        return [
            $userData = [$fullname, $username, $password, $email, $roles]
            
        ];
    }
 
 
}

