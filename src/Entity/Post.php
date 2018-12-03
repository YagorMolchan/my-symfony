<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;





/**
 * @ORM\Entity(repositoryClass="App\Repository\PostRepository") *
 */
class Post
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @Assert\NotBlank(message="Заголовок обызательно должен быть заполнен!")
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $summary;

    /**
     * @Assert\NotBlank(message="Содержимое контента обызательно должно быть заполнено!")
     * @ORM\Column(type="text")
     */
    private $body;


    /**
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="post", cascade={"persist", "remove"})
     */
    private $comments;




    /**
     * @ORM\Column(type="datetime")
     */
    private $created;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Loice", mappedBy="post", cascade={"persist","remove"})
     */
    private $loices;


    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->loices = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getSummary()
    {
        return $this->summary;
    }


    /**
     * @param mixed $summary
     */
    public function setSummary($summary): void
    {
        $this->summary = $summary;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body): void
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param mixed $comments
     */
    public function setComments($comments): void
    {
        $this->comments = $comments;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created): void
    {
        $this->created = $created;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }





    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Loice[]
     */
    public function getLoices(): Collection
    {
        return $this->loices;
    }

    public function addLoice(Loice $loice): self
    {
        if (!$this->loices->contains($loice)) {
            $this->loices[] = $loice;
            $loice->setPost($this);
        }

        return $this;
    }

    public function removeLoice(Loice $loice): self
    {
        if ($this->loices->contains($loice)) {
            $this->loices->removeElement($loice);
            // set the owning side to null (unless already changed)
            if ($loice->getPost() === $this) {
                $loice->setPost(null);
            }
        }

        return $this;
    }



}
