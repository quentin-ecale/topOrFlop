<?php

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use TopOrFlopBundle\Entity\User;

/**
 * Created by PhpStorm.
 * User: quentin
 * Date: 05/12/16
 * Time: 16:30
 */
class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;
    //Exemple : on aurait pu utiliser un trait a la place d'avoir le container et la methode
//    use \Symfony\Component\DependencyInjection\ContainerAwareTrait

    public function load(ObjectManager $manager)
    {
        $userData = array(
            'chuck' => array(
                'email'    => 'chuck@email.tld',
                'password' => 'password',
            ),
            'jean-claude' => array(
                'email'    => 'jean-claude@email.tld',
                'password' => 'password',
            ),
        );

        $userManager = $this->container->get('fos_user.user_manager');

        foreach ($userData as $userName => $userData) {
            $user = new User();
            $user->setUsername($userName);
            $user->setEmail($userData['email']);
            $user->setPlainPassword($userData['password']);
            $user->setEnabled(true);

            $userManager->updatePassword($user);

            $manager->persist($user);

            $this->addReference(sprintf('user-%s', $userName), $user);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 20;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}