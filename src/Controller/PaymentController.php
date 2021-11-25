<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Payment;
use App\Entity\Participant;
use App\Entity\Campaign;
use App\Form\PaymentType;
use App\Form\ParticipantType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/payment")
 */
class PaymentController extends AbstractController
{

    /**
     * @Route("/new/{id}", name="payment_new", methods={"GET","POST"})
     */
    public function new(Request $request, Campaign $campaign)
    {

        $valueAmount = $request->request->get('amount');
        $pay = [
            'payment' => new Payment(),
            'participant' => new Participant(),
        ];

        $form = $this->createFormBuilder($pay)
            ->add('payment', PaymentType::class)
            ->add('participant', ParticipantType::class)
            ->getForm();


        $payment = $pay['payment'];
        $form->handleRequest($request);
            
        if ($form->isSubmitted() && $form->isValid()  ) {
            
            $participant = $pay['participant'];
            $participant->setCampaign($campaign);
            $payment->setParticipant($participant);
            //Stripe 
            $this->stripeProcessing($payment, $request);

            //EntityManager
            $this->entityManagerProcessing($payment);
            

            return $this->redirectToRoute('campaign_show', [
                'id' => $campaign->getId(),
                'campaign' => $campaign,
                'amount' => $payment->getAmount(),
                ]);
        }

        return $this->render('payment/new.html.twig', [
            'payment' => $payment,
            'campaign' => $campaign,
            'form' => $form->createView(),
            'valueAmount' => $valueAmount,

        ]);
    }

    public function stripeProcessing($payment, $request)
    {
        try{
            \Stripe\Stripe::setApiKey($this->getParameter('key_stripe'));

            $charge = \Stripe\PaymentIntent::create([
                'amount' => $payment->getAmount()*100,
                'currency' => 'eur',
                // Verify your integration in this guide by including this parameter
                'metadata' => ['integration_check' => 'accept_a_payment'],
            ]);
            echo 'Merci pour votre participation';
        }
        catch(\Exception $e){
            dd('erreur payment',$e,$e->getMessage());
        }
    }
    public function entityManagerProcessing($payment)
    {

        $payment->setCreatedAt(new DateTime());
        $payment->setUpdatedAt(new DateTime());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($payment);
        $entityManager->persist($payment->getParticipant());
        $entityManager->flush();
    }
}