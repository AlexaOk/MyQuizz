<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Question
 *
 * @ORM\Table(name="question")
 * @ORM\Entity
 */
class Question
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="question", type="string", length=255, nullable=true)
     */
    private $question;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Category;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reponse", mappedBy="Question")
     */
    private $reponses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\History", mappedBy="question_id")
     */
    private $histories;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
        $this->history_id = new ArrayCollection();
        $this->histories = new ArrayCollection();
    }

    public function __toString()
    {
      return $this->question;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(?string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getCategory(): ?Categorie
    {
        return $this->Category;
    }

    public function setCategory(?Categorie $Category): self
    {
        $this->Category = $Category;

        return $this;
    }

    /**
     * @return Collection|Reponse[]
     */
    public function getReponses(): Collection
    {
        return $this->reponses;
    }

    public function addReponse(Reponse $reponse): self
    {
        if (!$this->reponses->contains($reponse)) {
            $this->reponses[] = $reponse;
            $reponse->setQuestion($this);
        }

        return $this;
    }

    public function removeReponse(Reponse $reponse): self
    {
        if ($this->reponses->contains($reponse)) {
            $this->reponses->removeElement($reponse);
            // set the owning side to null (unless already changed)
            if ($reponse->getQuestion() === $this) {
                $reponse->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|History[]
     */
    public function getHistoryId(): Collection
    {
        return $this->history_id;
    }

    public function addHistoryId(History $historyId): self
    {
        if (!$this->history_id->contains($historyId)) {
            $this->history_id[] = $historyId;
            $historyId->setQuestionId($this);
        }

        return $this;
    }

    public function removeHistoryId(History $historyId): self
    {
        if ($this->history_id->contains($historyId)) {
            $this->history_id->removeElement($historyId);
            // set the owning side to null (unless already changed)
            if ($historyId->getQuestionId() === $this) {
                $historyId->setQuestionId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|History[]
     */
    public function getHistories(): Collection
    {
        return $this->histories;
    }

    public function addHistory(History $history): self
    {
        if (!$this->histories->contains($history)) {
            $this->histories[] = $history;
            $history->setQuestionId($this);
        }

        return $this;
    }

    public function removeHistory(History $history): self
    {
        if ($this->histories->contains($history)) {
            $this->histories->removeElement($history);
            // set the owning side to null (unless already changed)
            if ($history->getQuestionId() === $this) {
                $history->setQuestionId(null);
            }
        }

        return $this;
    }
}
