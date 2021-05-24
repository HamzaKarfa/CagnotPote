<?php

namespace App\Controller;
use App\Entity\Campaign;
use App\Entity\Participant;
use App\Entity\Payment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $campaigns = $this->getDoctrine()
        ->getRepository(Campaign::class)
        ->findAll();


    //Participant et Payement existant
    //TOUS LES PARTICIPANTS

    
        $participants = $this->getDoctrine()
        ->getRepository(Participant::class)
        ->findBy(array('campaign' => $campaigns));

    //TOUS LES PAYEMENTS
        $payments = $this->getDoctrine()
        ->getRepository(Payment::class)
        ->findBy(array('participant' => $participants));



        return $this->render('home/index.html.twig', [
            'campaigns' => $campaigns,
            'payments' => $payments,
            'participants' => $participants,
        ]);
    }
}
