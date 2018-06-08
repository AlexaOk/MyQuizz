<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HistoryRepository")
 */
class History
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="histories")
     */
    private $user_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="histories")
     */
    private $question_id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Reponse", inversedBy="histories")
     */
    private $reponse_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $Created_at;

    public function __toString()
    {
      return (string) $this->id; 
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getQuestionId(): ?Question
    {
        return $this->question_id;
    }

    public function setQuestionId(?Question $question_id): self
    {
        $this->question_id = $question_id;

        return $this;
    }

    public function getReponseId(): ?Reponse
    {
        return $this->reponse_id;
    }

    public function setReponseId(?Reponse $reponse_id): self
    {
        $this->reponse_id = $reponse_id;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->Created_at;
    }

    public function setCreatedAt(\DateTimeInterface $Created_at): self
    {
        $this->Created_at = $Created_at;

        return $this;
    }
}
