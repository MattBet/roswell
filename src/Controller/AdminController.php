<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Post;
use App\Form\NewPostType;
use App\Form\RegisterType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\File\File;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $posts = $this->getDoctrine()->getRepository(Post::class)->findAll();

        dump($posts);
        return $this->render('admin/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/admin/delete/{id}", name="delete_post")
     */
    public function deleteAction($id)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();


        return $this->redirectToRoute("home");
    }

    /**
     * @Route("/admin/edit/{id}", name="edit_post")
     */
    public function postAction(Request $request, $id)
    {
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);

        if (!$post)
        {
            throw $this->createNotFoundException(
                "Aucun article correspondant à l'id" . $id
            );
        }

        $formEdit = $this->createFormBuilder($post)
            ->add('id', TextType::class, array(
                'disabled' => true
            ))
            ->add('title', TextType::class)
            ->add('body', TextareaType::class)
            ->add('edit', SubmitType::class)
            ->getForm()
        ;


        $formEdit->handleRequest($request);

        if($formEdit->isSubmitted() && $formEdit->isValid())
        {
            $post = $formEdit->getData();
            $em = $this->getDoctrine()->getManager();
            $post->setUpdatedAt(new \DateTime('now'));

            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('admin/edit.html.twig', array(
            'formEdit' => $formEdit->createView()
        ));

    }

    /**
     * @Route("/admin/new", name="new_post")
     */
    public function newAction(Request $request)
    {
        $post = new Post();

        $form = $this->createForm(NewPostType::class, $post);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $file = $form->get('image')->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            $file->move(
                $this->getParameter('image_directory'),$fileName
            );

            $post->setImage($fileName);
            $post->setCreatedAt(new \DateTime('now'));
            $post->setUpdatedAt(new \DateTime('now'));

            $post =
                $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);

            $entityManager->flush();

            return $this->redirectToRoute("admin");
        }
        return $this->render('admin/new.html.twig', array(
            'newForm' => $form->createView()));
    }








    /**
     * @Route("/login_door", name="login_door")
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('admin/login.html.twig', array(
            'error' => $error
        ));

    }

    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 3) Encode the password (you could also do this via Doctrine listener)
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            // 4) save the User!
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // ... do any other work - like sending them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('home');
        }

        return $this->render(
            'admin/register.html.twig', array('formRegister' => $form->createView())
        );

    }
}
