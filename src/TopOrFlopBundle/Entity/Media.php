<?php

namespace TopOrFlopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Media
 *
 * @ORM\Table(name="media")
 * @ORM\Entity(repositoryClass="TopOrFlopBundle\Repository\MediaRepository")
 */
class Media
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255)
     *
     * @Assert\NotBlank()
     * @Assert\Url()
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     *
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * @var float
     *
     * @ORM\Column(name="average", type="float", nullable=true)
     */
    private $average;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="TopOrFlopBundle\Entity\Vote",
     *     mappedBy="media",
     *     cascade={"persist"}
     * )
     */
    private $votes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->votes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Calculate the average score
     */
    public function computeAverageScore()
    {
        if (0 < $count = count($this->votes)) {
            $total = 0;

            foreach ($this->votes as $vote) {
                $total += $vote->getScore();
            }

            $this->average = $total / $count;
        }
    }

    /**
     * Whether the user has already voted for this media or not
     *
     * @param User $media
     * @return boolean
     */
    public function hasUserAlreadyVoted(User $user)
    {
        foreach ($this->votes as $vote) {
            if ($vote->getUser() == $user) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Media
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Media
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set average
     *
     * @param float $average
     *
     * @return Media
     */
    public function setAverage($average)
    {
        $this->average = $average;

        return $this;
    }

    /**
     * Get average
     *
     * @return float
     */
    public function getAverage()
    {
        return $this->average;
    }

    /**
     * Add vote
     *
     * @param \TopOrFlopBundle\Entity\Vote $vote
     *
     * @return Media
     */
    public function addVote(\TopOrFlopBundle\Entity\Vote $vote)
    {
        $vote->setMedia($this);
        $this->votes[] = $vote;

        $this->computeAverageScore();

        return $this;
    }

    /**
     * Remove vote
     *
     * @param \TopOrFlopBundle\Entity\Vote $vote
     */
    public function removeVote(\TopOrFlopBundle\Entity\Vote $vote)
    {
        $this->votes->removeElement($vote);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVotes()
    {
        return $this->votes;
    }
}
