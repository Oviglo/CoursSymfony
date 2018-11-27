<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Assert\Length(
     *      min = 2,
     *      max = 60
     * )
     * @Assert\NotBlank(message="validator.notblank")
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Article", mappedBy="categories")
     * @var ?\Doctrine\Common\Collections\ArrayCollection
     */
    private $articles;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Get the value of articles
     *
     * @return  ?\Doctrine\Common\Collections\ArrayCollection
     */ 
    public function getArticles()
    {
        return $this->articles;
    }

    /**
     * Set the value of articles
     *
     * @param  ?\Doctrine\Common\Collections\ArrayCollection  $articles
     *
     * @return  self
     */ 
    public function setArticles(?\Doctrine\Common\Collections\ArrayCollection $articles)
    {
        $this->articles = $articles;

        return $this;
    }
}
