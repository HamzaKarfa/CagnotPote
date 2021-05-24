<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/Participant")
 */
class ParticipantController extends AbstractController
{

    /**
     * @Route("/", name="", methods={"GET","POST"})
     */
    public function new()
    {
        $participant = new Participant();
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest();
        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager = $this->getDoctrine()->getManager();
            //$participant->setId(); //il faut définir l'id de la campagne !
            $entityManager->persist($participant);
            $entityManager->flush();

            return $this->redirectToRoute('participant_new', ['id' => $participant->getId()]);
        }

        return $this->render('campaign/payement.html.twig', [
            'participant' => $participant,
            'form' => $form->createView(),
        ]);
    }
}
