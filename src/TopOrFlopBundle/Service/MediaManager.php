<?php

namespace TopOrFlopBundle\Service;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use TopOrFlopBundle\Entity\Media;
use TopOrFlopBundle\Entity\Vote;


/**
 * Created by PhpStorm.
 * User: quentin
 * Date: 06/12/16
 * Time: 14:39
 */
class MediaManager
{

    private $em;

    private $tokenStorage;

    private $securityChecker;

    public function __construct(
        EntityManagerInterface $em,
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $securityChecker
    ) {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->securityChecker = $securityChecker;
    }

    /**
     * Get the next media to display depending on the user
     *
     * @return Media
     */
    public function getNextMedia()
    {
        $mediaRepository = $this->em->getRepository(Media::class);

        // user authenticated
        if ($this->securityChecker->isGranted('ROLE_USER')) {
            $user = $this->tokenStorage->getToken()->getUser();

            $media = $mediaRepository->getNewMediaForUser($user);

            // found a media the user hasn't voted for yet
            if ($media instanceof Media) {
                return $media;
            }
        }

        // default behavior: just get a random media
        return $mediaRepository->getRandomMedia();
    }

    /**
     * Get a media object from an id, with votes hydrated
     *
     * @param integer $id
     * @return Media
     */
    public function getMedia($id)
    {
        $mediaRepository = $this->em->getRepository(Media::class);

        return $mediaRepository->findOneById($id);
    }

    /**
     * Get a new vote object for current user and given media
     *
     * @param Media $media
     * @return Vote
     */
    public function getNewVote(Media $media)
    {
        if (!$this->securityChecker->isGranted('ROLE_USER')) {
            return null;
        }

        $user = $this->tokenStorage->getToken()->getUser();

        $vote = new Vote();
        $vote->setUser($user);
        $vote->setMedia($media);

        return $vote;
    }

    /**
     * Save a vote and update the average score
     *
     * @param Vote $vote
     */
    public function saveVote(Vote $vote)
    {
        $media = $vote->getMedia();
        $media->addVote($vote);
        //ici on a pas besoin de faire le persist car sur le Media, on a mis dans l'ORM un cascade={"persist"]
        $this->em->flush();
    }
}