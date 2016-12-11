<?php

namespace TopOrFlopBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use TopOrFlopBundle\Entity\Media;
use TopOrFlopBundle\Form\MediaType;

/**
 * Class AdminController
 * @package TopOrFlopBundle\Controller
 *
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin_new")
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @internal param $name
     */
    public function newAction(Request $request)
    {
        $media = new Media();

        $form = $this->createForm(MediaType::class, $media);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($media);
            $em->flush();

            return $this->redirect(
                $this->generateUrl('show_media', array('id' => $media->getId())));
        }

        return $this->render(
            'TopOrFlopBundle:Backend:new.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }
}
