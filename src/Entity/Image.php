<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var UploadedFile
     * @Assert\Image(
     *  maxSize = "2M"
     * )
     */
    private $file;

    /**
     * @var ?string
     * Chemin de l'ancien fichier
     */
    private $oldPath;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get maxSize = "2M"
     *
     * @return  UploadedFile
     */ 
    public function getFile() : ?UploadedFile
    {
        return $this->file;
    }

    /**
     * Set maxSize = "2M"
     *
     * @param  UploadedFile  $file  maxSize = "2M"
     *
     * @return  self
     */ 
    public function setFile(UploadedFile $file)
    {
        $this->oldPath = $this->path; // Sauvegarde le chemin de l'ancien fichier pour le supprimer lors de l'upload du nouveau 
        $this->path = ''; // Modifie cette valeur pour activer la modif Doctrine 
        $this->file = $file;

        return $this;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function generatePath()
    {
        if ($this->file instanceof UploadedFile) {
            // Génére le chemin du fichier à uploader
            $this->path = uniqid('img_') . '.' . $this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        // Supprimer l'ancien fichier s'il existe
        if (is_file($this->getPublicRootDir() . $this->oldPath)) {
            unlink($this->getPublicRootDir() . $this->oldPath);
        }

        if ($this->file instanceof UploadedFile) {
            $this->file->move(
                $this->getPublicRootDir(), // Vers le dossier public/uploads
                $this->path
            );
        }
    }

    public function getPublicRootDir()
    {
        return __DIR__ . '/../../public/uploads/';
    }

    /**
     * @ORM\PreRemove()
     */
    public function remove()
    {
        // Test si le fichier existe
        if (is_file($this->getPublicRootDir() . $this->path)) {

            unlink($this->getPublicRootDir() . $this->path);
        }
    }
}
