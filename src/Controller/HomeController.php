<?php

namespace App\Controller;
use App\Entity\Campaign;
use App\Entity\Participant;
use App\Entity\Payment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
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
    /**
     * @Route("/test", name="test")
     */
    public function test()
    {
        $response = $this->client->request(
            'GET',
            'https://maps.googleapis.com/maps/api/place/autocomplete/json?input=Roan&types=geocode&language=fr&key=AIzaSyDNwlL_Fbumi8lvNKIctvzrIKxiSZITz7I'
        );

        // $statusCode = 200
        $contentType = $response->getHeaders()['content-type'][0];
        // $contentType = 'application/json'
        $content = $response->getContent();
        // $content = '{"id":521583, "name":"symfony-docs", ...}'
        $content = $response->toArray();
        // $content = ['id' => 521583, 'name' => 'symfony-docs', ...]
        dd($content);
    
        

        return $this->render('home/index.html.twig', [
        
        ]);
    }
}
