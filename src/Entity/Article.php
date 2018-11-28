<?php 

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 */
class Article 
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    private $content;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="articles")
     * @var ?\Doctrine\Common\Collections\ArrayCollection
     */
    private $categories;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Image", cascade={"all"}, orphanRemoval=true)
     * @var ?\App\Entity\Image
     */
    private $image;

    /**
     * @var bool
     */
    private $deleteImage;

    public function __construct()
    {
        $this->deleteImage = false;
    }

    /**
     * Get the value of id
     *
     * @return  int
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of content
     *
     * @return  string
     */ 
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @param  string  $content
     *
     * @return  self
     */ 
    public function setContent(string $content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of categories
     *
     * @return  ?\Doctrine\Common\Collections\ArrayCollection
     */ 
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set the value of categories
     *
     * @param  ?\Doctrine\Common\Collections\ArrayCollection  $categories
     *
     * @return  self
     */ 
    public function setCategories(?\Doctrine\Common\Collections\ArrayCollection $categories)
    {
        $this->categories = $categories;

        return $this;
    }

    /**
     * Get the value of image
     *
     * @return  ?\App\Entity\Image
     */ 
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of image
     *
     * @param  ?\App\Entity\Image  $image
     *
     * @return  self
     */ 
    public function setImage(?\App\Entity\Image $image)
    {
        if ($image instanceof \App\Entity\Image && !is_null($image->getFile())) { // Test s'il une image est envoyée
            $this->image = $image;
        }
        
        return $this;
    }

    /**
     * Get the value of deleteImage
     *
     * @return  bool
     */ 
    public function getDeleteImage()
    {
        return $this->deleteImage;
    }

    /**
     * Set the value of deleteImage
     *
     * @param  bool  $deleteImage
     *
     * @return  self
     */ 
    public function setDeleteImage(bool $deleteImage)
    {
        $this->deleteImage = $deleteImage;
        if ($deleteImage) {
            $this->image = null;
        }

        return $this;
    }
}