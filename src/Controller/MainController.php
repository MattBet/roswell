<?php

namespace App\Controller;

use App\Entity\Crew;
use App\Entity\Post;
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
        $posts = $this->getDoctrine()->getRepository(Post::class)->latestPosts();

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

        dump($posts);
        return $this->render('index.html.twig', [
            'crew' => $crew,
            'posts' => $posts,
            'formNewsletter' => $form->createView(),
        ]);
    }

    /**
     * @Route("post/{id}", name="post_show")
     */
    public function show($id, Request $request)
    {

        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);

        return $this->render('post/show.html.twig', array(
            'post' => $post,
        ));
    }
}
