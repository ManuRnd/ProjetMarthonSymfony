<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="votes")
 * @UniqueEntity(
 *     fields={"user", "training"},
 *     message="Un utilisateur ne peut noter qu'une seule fois un article."
 * )
 * @ORM\Entity(repositoryClass="App\Repository\VoteRepository")
 */
class Vote
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;
    /**
     * @var Training
     * @ORM\ManyToOne(targetEntity="App\Entity\Training", inversedBy="votes")
     */
    private $training;
    /**
     * @var int
     * @ORM\Column(columnDefinition="TINYINT(4)", type="integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 5,
     *      minMessage = "La note minimale d'un article est de {{ limit }}/5.",
     *      maxMessage = "La note maximale d'un article est de {{ limit }}/{{ limit }}."
     * )
     * @Assert\Type(
     *     type="integer",
     *     message="La valeur {{ value }} n'est pas un {{ type }}."
     * )
     */
    private $value;

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
     * @return User
     */
    public function getUser(): User {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user) {
        $this->user = $user;
    }

    /**
     * @return Training
     */
    public function getTraining(): Training {
        return $this->training;
    }

    /**
     * @param Training $training
     */
    public function setTraining(Training $training) {
        $this->training = $training;
    }

    /**
     * @return int
     */
    public function getValue(){
        return $this->value;
    }

    /**
     * @param int $value
     */
    public function setValue(int $value) {
        $this->value = $value;
    }
}
