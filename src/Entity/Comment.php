<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="comments")
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Training", inversedBy="comments")
     */
    private $training;
    /**
     * @var string
     * @ORM\Column(columnDefinition="TEXT")
     * @Assert\NotBlank()
     */
    private $content;
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
     * Comment constructor.
     */

    /**
     * @return Training
     */
    public function getTraining() :Training{
        return $this->training;
    }

    /**
     * @param Training $training
     */
    public function setTraining(Training $training) {
        $this->training = $training;
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
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent(string $content) {
        $this->content = $content;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime {
        return $this->created_at;
    }

    /**
     * @param \DateTime $created_at
     */
    public function setCreatedAt(\DateTime $created_at) {
        $this->created_at = $created_at;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime {
        return $this->updated_at;
    }

    /**
     * @param \DateTime $updated_at
     */
    public function setUpdatedAt(\DateTime $updated_at) {
        $this->updated_at = $updated_at;
    }

}
