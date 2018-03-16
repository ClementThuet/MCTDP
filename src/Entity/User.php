<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
  * @ORM\Table(name="user")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;
 
    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    private $username;
 
    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    private $email;
 
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $password;
 
    /**
     * @var array
     *
     * @ORM\Column(type="json")
     */
    private $roles = [];
 
    public function getId(): int
    {
        return $this->id;
    }
 
    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }
 
    public function getFullName(): string
    {
        return $this->fullName;
    }
 
    public function getUsername(): string
    {
        return $this->username;
    }
 
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }
 
    public function getEmail(): string
    {
        return $this->email;
    }
 
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
 
    public function getPassword()
    {
        return $this->password;
    }
 
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
 
    /**
     * Retourne les rôles de l'user
     */
    public function getRoles(): array
    {
       return array('ROLE_USER');
    }
 
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }
 
    /**
     * Retour le salt qui a servi à coder le mot de passe
     *
     * {@inheritdoc}
     */
    public function getSalt()
    {
        return null;
    }
 
    /**
     * Removes sensitive data from the user.
     *
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
        // Nous n'avons pas besoin de cette methode car nous n'utilions pas de plainPassword
        // Mais elle est obligatoire car comprise dans l'interface UserInterface
        // $this->plainPassword = null;
    }
 
    /**
     * {@inheritdoc}
     */
    public function serialize() {
        return serialize(array(
            $this->id,
            $this->username,
            $this->password,
            ));
    }
    /**
     * {@inheritdoc}
     */
    public function unserialize($data) {
        list (
            $this->id,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($data);
    }
}