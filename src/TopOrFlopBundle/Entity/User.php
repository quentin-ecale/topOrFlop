<?php

namespace TopOrFlopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as FOSUser;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="TopOrFlopBundle\Repository\UserRepository")
 */
class User extends FOSUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\OneToMany(
     *     targetEntity="TopOrFlopBundle\Entity\Vote",
     *     mappedBy="user",
     *     cascade={"persist"}
     * )
     */
    private $votes;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->votes = new ArrayCollection();
    }

    /**
     * Add vote
     *
     * @param \TopOrFlopBundle\Entity\Vote $vote
     *
     * @return User
     */
    public function addVote(\TopOrFlopBundle\Entity\Vote $vote)
    {
        $this->votes[] = $vote;

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
