<?php

use \Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use \Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use TopOrFlopBundle\Entity\Vote;

/**
 * Created by PhpStorm.
 * User: quentin
 * Date: 05/12/16
 * Time: 16:33
 */
class LoadVoteData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $media = $manager->merge($this->getReference('media-'.$i));

            $vote1 = new Vote();
            $vote1->setScore(rand(1, 10));
            $vote1->setUser($this->getReference('user-chuck'));
            $media->addVote($vote1);

            $vote2 = new Vote();
            $vote2->setScore(rand(1, 10));
            $vote2->setUser($this->getReference('user-jean-claude'));
            $media->addVote($vote2);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 30;
    }
}