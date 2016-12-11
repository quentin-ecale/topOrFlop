<?php

namespace TopOrFlopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Vote
 *
 * @ORM\Table(
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="vote_unique_idx", columns={"user_id", "media_id"})
 *     }
 * )
 *
 * @ORM\Table(name="vote")
 * @ORM\Entity(repositoryClass="TopOrFlopBundle\Repository\VoteRepository")
 *
 * @UniqueEntity({"user", "media"})
 */
class Vote
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
     * @var int
     *
     * @ORM\Column(name="score", type="integer")
     *
     * @Assert\NotBlank()
     */
    private $score;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     *
     * @Assert\NotBlank()
     * @Assert\DateTime()
     */
    private $date;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="TopOrFlopBundle\Entity\User",
     *     inversedBy="votes")
     */
    private $user;

    /**
     * @var Media
     *
     * @ORM\ManyToOne(targetEntity="TopOrFlopBundle\Entity\Media",
     *     inversedBy="votes")
     */
    private $media;

    /**
     * Vote constructor.
     */
    public function __construct()
    {
        $this->date = new \DateTime('now');
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
     * Set score
     *
     * @param integer $score
     *
     * @return Vote
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Vote
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return Vote
     */
    public function setUser(\TopOrFlopBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \stdClass
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set media
     *
     * @param Media $media
     *
     * @return Vote
     */
    public function setMedia(\TopOrFlopBundle\Entity\Media $media)
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Get media
     *
     * @return \stdClass
     */
    public function getMedia()
    {
        return $this->media;
    }
}
