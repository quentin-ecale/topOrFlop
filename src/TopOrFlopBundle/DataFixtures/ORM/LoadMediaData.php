<?php

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use TopOrFlopBundle\Entity\Media;

/**
 * Created by PhpStorm.
 * User: quentin
 * Date: 05/12/16
 * Time: 16:16
 */
class LoadMediaData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $urls = array(
            'http://images.wikia.com/desencyclopedie/images/1/17/331px-Longcat.jpg',
            'http://i1.kym-cdn.com/entries/icons/original/000/000/774/lime-cat.jpg',
            'http://shechive.files.wordpress.com/2011/05/hover-cat-20.jpg',
        );

        for ($i=1; $i<=10; $i++) {
            $image = new Media();
            $image->setTitle('image '.$i);
            $image->setUrl($urls[$i%3]);

            $manager->persist($image);

            $this->addReference('media-'.$i, $image);
        }

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 10;
    }
}