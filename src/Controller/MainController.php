<?php

namespace App\Controller;

use App\Entity\Crew;
use App\Entity\Subscribers;
use App\Form\NewsletterType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index(Request $request)
    {
        $crew = $this->getDoctrine()->getRepository(Crew::class)->findAll();

        $sub = new Subscribers();
        $form = $this->createForm(NewsletterType::class, $sub);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sub);
            $em->flush();

            return $this->redirectToRoute('home');
        }
        return $this->render('index.html.twig', [
            'crew' => $crew,
            'formNewsletter' => $form->createView(),
        ]);
    }
}
