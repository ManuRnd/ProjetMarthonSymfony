<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="name", message="Ce nom a déjà été pris.")
 * @UniqueEntity(fields="email", message="Cet email est déjà utilisé.")
 * )
 */
class User implements UserInterface, \Serializable {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var string
     * @ORM\Column(type="string", length=45)
     * @Assert\Length(
     *     min = 2,
     *     max = 45,
     *     minMessage = "Le nom d'un utilisateur doit faire {{ limit }} caractères au minimum.",
     *     maxMessage = "Le nom d'un utilisateur doit faire {{ limit }} caractères au maximum."
     * )
     */
    private $name;
    /**
     * @var string
     * @ORM\Column(type="string",unique=true)
     * @Assert\Email(
     *     message = "L'email '{{ value }}' n'est pas un email valide.",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     * @var Article[]
     * @ORM\OneToMany(targetEntity="App\Entity\Article",mappedBy="user")
     */
    private $articles;

    /**
     * @return mixed
     */
    public function getPlainPassword() {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword) {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $password;
    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $remember_token;
    /**
     * @var bool
     * @ORM\Column(columnDefinition="TINYINT(1)")
     */
    private $admin;
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $created_at;
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * User constructor.
     */
    public function __construct() {
        $this->roles = array("ROLE_USER");
        $this->admin = 0;
        $this->email = "";
        $this->remember_token = "";
        $this->password = "";
        $this->name = "";
        $this->created_at = $this->updated_at = new \DateTime('now');
        $this->articles = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name) {
        $this->name = $name;
    }

    /**
     * @return Article[]
     */
    public function getArticles(): ArrayCollection {
        return $this->articles;
    }

    /**
     * @param Article[] $articles
     */
    public function setArticles(ArrayCollection $articles) {
        $this->articles = $articles;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email) {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getRememberToken(): string {
        return $this->remember_token;
    }

    /**
     * @param string $remember_token
     */
    public function setRememberToken(string $remember_token) {
        $this->remember_token = $remember_token;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool {
        return $this->admin;
    }

    /**
     * @param bool $admin
     */
    public function setAdmin(bool $admin) {
        $this->admin = $admin;
    }

    /**
     * @return \Datetime
     */
    public function getCreatedAt() {
        return $this->created_at;
    }

    /**
     * @param \DateTime $created_at
     */
    public function setCreatedAt(\DateTime $created_at) {
        $this->created_at = $created_at;
    }

    /**
     * @return \Datetime
     */
    public function getUpdatedAt() {
        return $this->updated_at;
    }

    /**
     * @param \DateTime $updated_at
     */
    public function setUpdatedAt(\DateTime $updated_at) {
        $this->updated_at = $updated_at;
    }


    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize() {
        return serialize(
            array(
                $this->id,
                $this->email,
                $this->name,
                $this->password,
            )
        );
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized) {
        list (
            $this->id,
            $this->email,
            $this->name,
            $this->password,
            ) = unserialize($serialized);
    }

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles() {
        if ($this->admin) {
            return array('ROLE_USER','ROLE_ADMIN');
        }

        return array('ROLE_USER');
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * This should be the encoded password. On authentication, a plain-text
     * password will be salted, encoded, and then compared to this value.
     *
     * @return string The password
     */
    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt() {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername() {
        return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials() {
        // TODO: Implement eraseCredentials() method.
    }
}
