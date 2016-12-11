<?php

namespace TopOrFlopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use TopOrFlopBundle\Entity\Media;
use TopOrFlopBundle\Entity\Vote;
use TopOrFlopBundle\Form\VoteType;

//use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="index")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $users = array();
        $parameters = ["data" => "toto", "users" => $users];

        return $this->render('TopOrFlopBundle:Default:index.html.twig', $parameters);
    }

    /**
     * @Route("/show", name="show_random")
     */
    public function showRandomMediaAction()
    {
        $media = $this->get('top_or_flop.media_manager')->getNextMedia();

        return $this->redirect(
            $this->generateUrl('show_media', array('id' => $media->getId()))
        );

    }

    /**
     * @Route("/show/{id}", requirements={"id" = "\d+"}, name="show_media")
     */
    public function showMediaAction($id)
    {
        $mediaManager = $this->get('top_or_flop.media_manager');

        $media = $mediaManager->getMedia($id);

        if ($media === null) {
            throw $this->createNotFoundException();
        }

        $vote = $mediaManager->getNewVote($media);
        if ($vote instanceof Vote) {
            $form = $this->createForm(VoteType::class, $vote);
        }

        //on pourrait checker ici si le form est renovyé et valide, enregistrer les informations

        return $this->render(
            'TopOrFlopBundle:Default:show.html.twig',
            array(
                'media' => $media,
                'form' => isset($form) ? $form->createView() : null,
            )
        );
    }

    /**
     * @Route("/vote/{id}", name="vote_media")
     * @Method({"POST"})
     *
     * @param Request $request
     * @param integer $id
     */
    public function voteMediaAction(Request $request, $id)
    {
        $mediaManager = $this->get('top_or_flop.media_manager');

        $media = $mediaManager->getMedia($id);
        if ($media === null) {
            throw $this->createNotFoundException();
        }

        $vote = $mediaManager->getNewVote($media);

        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);

        $session = $request->getSession();

        if ($form->isSubmitted() && $form->isValid()) {
            $mediaManager->saveVote($vote);

            $session->getFlashBag()->add('success', 'Votre vote est enregistré');

            return $this->redirect(
                $this->generateUrl('show_media', array('id' => $media->getId()))
            );
        }

        $session->getFlashBag()->add('error', 'Une erreur est survenue');

        return $this->forward(
            'TopOrFlopBundle:Default:showMedia',
            array(
                'id' => $media->getId(),
            )
        );
    }

    /**
     * @Route("/tops", name="show_tops")
     */
    public function showTopsAction()
    {
        $tops = $this
            ->get('doctrine')
            ->getManager()
            ->getRepository('TopOrFlopBundle\Entity\Media')
            ->getTopMedias();

        return $this->render(
            'TopOrFlopBundle:Default:tops.html.twig',
            array(
                'tops' => $tops,
            )
        );
    }

    /**
     * @Route("/flops", name="show_flops")
     */
    public function showFlopsAction()
    {
        $flops = $this
            ->get('doctrine')
            ->getManager()
            ->getRepository('TopOrFlopBundle\Entity\Media')
            ->getTopMedias('ASC');

        return $this->render(
            'TopOrFlopBundle:Default:flops.html.twig',
            array(
                'flops' => $flops,
            )
        );
    }
}
