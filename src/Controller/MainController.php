<?php

namespace App\Controller;

use App\Entity\Crew;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $crew = $this->getDoctrine()->getRepository(Crew::class)->findAll();
        return $this->render('index.html.twig', [
            'crew' => $crew,
        ]);
    }
}
