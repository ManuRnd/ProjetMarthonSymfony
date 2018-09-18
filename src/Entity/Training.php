<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrainingRepository")
 * @UniqueEntity(fields="name", message="Ce nom d'entraînement a déjà été pris.")
 */
class Training {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var Media
     * @ORM\ManyToOne(targetEntity="App\Entity\Media")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", nullable=true)
     */
    private $media;
    /**
     * @var string
     * @ORM\Column(type="string", length=120)
     * @Assert\Length(
     *     min = 2,
     *     max = 120,
     *     minMessage = "Le nom d'un entraînement doit faire {{ limit }} caractères au minimum.",
     *     maxMessage = "Le nom d'un entraînement doit faire {{ limit }} caractères au maximum."
     * )
     */
    private $name;
    /**
     * @var Comment[]
     * @ORM\OneToMany(targetEntity="App\Entity\Comment",mappedBy="training")
     */
    private $comments;

    /**
     * @var Vote[]
     * @ORM\OneToMany(targetEntity="App\Entity\Vote",mappedBy="training")
     */
    private $votes;

    /**
     * @var string
     * @ORM\Column(columnDefinition="TEXT")
     */
    private $steps;
    /**
     * @var string
     * @ORM\Column(columnDefinition="TEXT")
     */
    private $muscles;
    /**
     * @var int
     * @ORM\Column(columnDefinition="TINYINT(4)")
     * @Assert\Range(
     *      min = 1,
     *      max = 3,
     *      invalidMessage = "La difficulté d'un entraînement est comprise entre 1 et 3."
     * )
     */
    private $difficulty;
    /**
     * @var \DateTime
     * @ORM\Column(type="time")
     */
    private $training_time;
    /**
     * @var \DateTime
     * @ORM\Column(type="time")
     */
    private $rest_time;
    /**
     * @var string
     * @ORM\Column(columnDefinition="TEXT")
     */
    private $materials;
    /**
     * @var string
     * @ORM\Column(columnDefinition="TEXT")
     */
    private $astuce;
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
     * Training constructor.
     */
    public function __construct() {
        $this->comments = new ArrayCollection();
        $this->votes = new ArrayCollection();
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
     * @return Media
     */
    public function getMedia(){
        return $this->media;
    }

    /**
     * @param Media $media
     */
    public function setMedia(Media $media) {
        $this->media = $media;
    }

    /**
     * @return string
     */
    public function getName(){
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name) {
        $this->name = $name;
    }

    /**
     * @return Comment[]
     */
    public function getComments(): array {
        return $this->comments;
    }

    /**
     * @param Comment[] $comments
     */
    public function setComments(array $comments) {
        $this->comments = $comments;
    }

    /**
     * @return Vote[]
     */
    public function getVotes(): array {
        return $this->votes;
    }

    /**
     * @param Vote[] $votes
     */
    public function setVotes(array $votes) {
        $this->votes = $votes;
    }

    /**
     * @return string
     */
    public function getSteps() {
        return $this->steps;
    }

    /**
     * @param string $steps
     */
    public function setSteps(string $steps) {
        $this->steps = $steps;
    }

    /**
     * @return string
     */
    public function getMuscles(){
        return $this->muscles;
    }

    /**
     * @param string $muscles
     */
    public function setMuscles(string $muscles) {
        $this->muscles = $muscles;
    }

    /**
     * @return int
     */
    public function getDifficulty(){
        return $this->difficulty;
    }

    /**
     * @param int $difficulty
     */
    public function setDifficulty(int $difficulty) {
        $this->difficulty = $difficulty;
    }

    /**
     * @return \DateTime
     */
    public function getTrainingTime() {
        return $this->training_time;
    }

    /**
     * @param \DateTime $training_time
     */
    public function setTrainingTime(\DateTime $training_time) {
        $this->training_time = $training_time;
    }

    /**
     * @return \DateTime
     */
    public function getRestTime() {
        return $this->rest_time;
    }

    /**
     * @param \DateTime $rest_time
     */
    public function setRestTime(\DateTime $rest_time) {
        $this->rest_time = $rest_time;
    }

    /**
     * @return string
     */
    public function getMaterials(){
        return $this->materials;
    }

    /**
     * @param string $materials
     */
    public function setMaterials(string $materials) {
        $this->materials = $materials;
    }

    /**
     * @return string
     */
    public function getAstuce() {
        return $this->astuce;
    }

    /**
     * @param string $astuce
     */
    public function setAstuce(string $astuce) {
        $this->astuce = $astuce;
    }

    /**
     * @return \DateTime
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
     * @return \DateTime
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


}
