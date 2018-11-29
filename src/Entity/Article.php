<?php 

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArticleRepository")
 * @ORM\HasLifecycleCallbacks
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

    /**
     * @var ?\DateTime
     * @ORM\Column(type="datetime")
     */
    private $dateCreate;

    /**
     * @var ?\DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateUpdate;

    /**
     * @var ?\App\Entity\User
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $user;

    /**
     * @var ?bool
     * @ORM\Column(type="boolean", nullable=true, options={"default" : true})
     */
    private $publish;

    public function __construct()
    {
        $this->deleteImage = false;
        $this->dateCreate =  new \DateTime;
        $this->dateUpdate = null;
        $this->publish = true;
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

    /**
     * Get the value of dateCreate
     *
     * @return  ?\DateTime
     */ 
    public function getDateCreate()
    {
        return $this->dateCreate;
    }

    /**
     * Get the value of dateUpdate
     *
     * @return  ?\DateTime
     */ 
    public function getDateUpdate()
    {
        return $this->dateUpdate;
    }

    /**
     * Methode appelée par Doctrine avant de faire l'update dans la base de données
     * @ORM\PreUpdate()
     */
    public function preUpdate()
    {
        $this->dateUpdate = new \DateTime;
    }

    /**
     * Get the value of user
     *
     * @return  ?\App\Entity\User
     */ 
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set the value of user
     *
     * @param  ?\App\Entity\User  $user
     *
     * @return  self
     */ 
    public function setUser(?\App\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get the value of publish
     *
     * @return  ?bool
     */ 
    public function getPublish()
    {
        return $this->publish;
    }

    /**
     * Set the value of publish
     *
     * @param  ?bool  $publish
     *
     * @return  self
     */ 
    public function setPublish(?bool $publish)
    {
        $this->publish = $publish;

        return $this;
    }
}