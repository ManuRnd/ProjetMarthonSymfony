<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="medias")
 * @ORM\Entity(repositoryClass="App\Repository\MediaRepository")
 * @UniqueEntity(fields="title", message="Ce titre de media a déjà été pris.")
 */
class Media
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var string
     * @ORM\Column(type="string", length=120)
     * @Assert\Length(
     *     min = 3,
     *     max = 120,
     *     minMessage = "Le titre d'un article doit faire {{ limit }} caractères au minimum.",
     *     maxMessage = "Le titre d'un article doit faire {{ limit }} caractères au maximum."
     * )
     */
    private $title;
    /**
     * @var string
     * @ORM\Column(type="string", length=300)
     * @Assert\Length(
     *     min = 7,
     *     max = 300,
     *     minMessage = "Le lien doit faire {{ limit }} caractères au minimum.",
     *     maxMessage = "Le lien doit faire {{ limit }} caractères au maximum."
     * )
     */
    private $path;
    /**
     * @var string
     * @ORM\Column(type="string", length=20)
     * @Assert\Length(
     *     min = 2,
     *     max = 20,
     *     minMessage = "Le type doit faire {{ limit }} caractères au minimum.",
     *     maxMessage = "Le type doit faire {{ limit }} caractères au maximum."
     * )
     */
    private $type;

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
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * @param string $title
     */
    public function setTitle(string $title) {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getPath(): string {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path) {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getType(): string {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type) {
        $this->type = $type;
    }
}
