<?php 

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var ?\Doctrine\Common\Collections\ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Article", mappedBy="user")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $articles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ArticleFollow", mappedBy="user", orphanRemoval=true)
     */
    private $followedArticles;

    public function __construct()
    {
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection;
        parent::__construct();
        $this->followedArticles = new ArrayCollection();
        // your own logic
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

    /**
     * @return Collection|ArticleFollow[]
     */
    public function getFollowedArticles(): Collection
    {
        return $this->followedArticles;
    }

    public function addFollowedArticle(ArticleFollow $followedArticle): self
    {
        if (!$this->followedArticles->contains($followedArticle)) {
            $this->followedArticles[] = $followedArticle;
            $followedArticle->setUser($this);
        }

        return $this;
    }

    public function removeFollowedArticle(ArticleFollow $followedArticle): self
    {
        if ($this->followedArticles->contains($followedArticle)) {
            $this->followedArticles->removeElement($followedArticle);
            // set the owning side to null (unless already changed)
            if ($followedArticle->getUser() === $this) {
                $followedArticle->setUser(null);
            }
        }

        return $this;
    }
}