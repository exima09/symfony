<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    /**
     * @Route("/user", name="user")
     */
    public function index()
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to your action: index(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();
        $user = new User();
        $user->setName('David');
        $user->setSubname('Juszczak');
        $user->setEmail('eximastudio@gmail.com');
        $user->setPassword('exima123');


        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($user);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id '.$user->getName());
    }
    /**
     * @Route("/user/show/{id}", name="show_id")
     */
    public function show($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if(!$user)
        {
            throw $this->createNotFoundException('Brak użytkownika nr '.$id);
        }
        return new Response('Poprawnie pobrano użytkownika: '.$user->getName().' '.$user->getSubname());
    }
    /**
     * @Route("/user/shows/{id}", name="shows_id")
     */
    public function shows(User $user)
    {

        return new Response('Poprawnie pobrano użytkownika: '.$user->getName().' '.$user->getSubname());
    }
    /**
     * @Route("/user/list", name="user_list")
     */
    public function list()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        return $this->render('user/list.html.twig', [
                    'users' => $users,
                ]);
    }
}
