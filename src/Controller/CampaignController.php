<?php

namespace App\Controller;

use App\Entity\Campaign;
use App\Entity\Participant;
use App\Entity\Payment;
use App\Form\CampaignType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;




/**
 * @Route("/campaign")
 */
class CampaignController extends AbstractController
{
    /**
     * @Route("/", name="campaign_index", methods={"GET"})
     */
    public function index(): Response
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


        //    dd($payments);
            // $pourcentage = $countPayment/$campaigns->getGoal()*100;

        return $this->render('campaign/index.html.twig', [
            'campaigns' => $campaigns,
            'payments' => $payments,
            'participants' => $participants,
        ]);
    }

    /**
     * @Route("/new", name="campaign_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $campaign = new Campaign();
        $form = $this->createForm(CampaignType::class, $campaign);

        $form->handleRequest($request);

        $valueInput = $request->request->get('cag_name');

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $campaign->setId(); //il faut définir l'id de la campagne !
            $entityManager->persist($campaign);
            $entityManager->flush();

            return $this->redirectToRoute('campaign_show', [
                'id' => $campaign->getId(),
                'campaign' => $campaign
            ]);
        }

        return $this->render('campaign/new.html.twig', [
            'campaign' => $campaign,
            'form' => $form->createView(),
            'valueInput' => $valueInput
        ]);
    }

    /**
     * @Route("/{id}", name="campaign_show", methods={"GET"})
     */
    public function show(Campaign $campaign,  Request $request): Response
    {
    
        
        //TOUS LES PARTICIPANTS
        $participants = $this->getDoctrine()
        ->getRepository(Participant::class)
        ->findBy(array('campaign' => $campaign));

        $countParticipant= count($participants);
        $payments = $this->getDoctrine()
        ->getRepository(Payment::class)
        ->findBy(array('participant' => $participants));

        $countPayment = 0;
        foreach ($payments as $payment) {
            $countPayment += $payment->getAmount();
        }

        // dd($payments);
        $pourcentage = $countPayment/$campaign->getGoal()*100;
        //TOUS LES PAYEMENTS
        // dd($payments, $participants);
        return $this->render('campaign/show.html.twig', [
            'campaign' => $campaign,
            'payments' =>  $payments ,
            'participantsCount' => $countParticipant,
            'totalPayment' => $countPayment,
            'pourcentage' => $pourcentage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="campaign_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Campaign $campaign): Response
    {
        $form = $this->createForm(CampaignType::class, $campaign);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('campaign_index');
        }

        return $this->render('campaign/edit.html.twig', [
            'campaign' => $campaign,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="campaign_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Campaign $campaign): Response
    {
        if ($this->isCsrfTokenValid('delete'.$campaign->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($campaign);
            $entityManager->flush();
        }

        return $this->redirectToRoute('campaign_index');
    }

}
