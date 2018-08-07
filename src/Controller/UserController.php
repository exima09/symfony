<?php

namespace App\Controller;

use App\Entity\User;
use http\Exception\UnexpectedValueException;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\LocaleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Classes\Login;

use Symfony\Component\HttpFoundation\Session\Session;

class UserController extends Controller
{

    /*public function index()
    /**
     * @Route("/user", name="user")
     */
    /*
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
    }*/
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
    /**
     * @Route("/user/add", name="user_add")
     */
    public function newUser(Request $request)
    {
        $user = new User();
        $form = $this->createFormBuilder($user)
            ->add('name', TextType::class)
            ->add('subname', TextType::class)
            ->add('email', TextType::class)
            ->add('password', PasswordType::class)
            ->add('add', SubmitType::class, array('label'=>'Add User'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $user = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();
            $entityManager = $this->getDoctrine()->getManager();
            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $entityManager->persist($user);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->redirectToRoute('user_list');
        }
        return $this->render('user/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    /**
     * @Route("/user/delete/{id}", name="delete_id")
     */
    public function delete($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if(!$user)
        {
            throw $this->createNotFoundException('Brak użytkownika nr '.$id);
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();
        return new Response('Poprawnie usunięto użytkownika: '.$user->getName().' '.$user->getSubname());
    }
    /**
     * @Route("/user/edit/{id}", name="edit_id")
     */
    public function edit($id,Request $request)
    {

        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if(!$user)
        {
            throw $this->createNotFoundException('Brak użytkownika nr '.$id);
        }

        $form = $this->createFormBuilder($user)
            ->add('name', TextType::class)
            ->add('subname', TextType::class)
            ->add('email', TextType::class)
            ->add('add', SubmitType::class, array('label'=>'Add User'))
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $user = $form->getData();

            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            // $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->persist($task);
            // $entityManager->flush();
            $entityManager = $this->getDoctrine()->getManager();
            // tell Doctrine you want to (eventually) save the Product (no queries yet)
            $entityManager->persist($user);

            // actually executes the queries (i.e. the INSERT query)
            $entityManager->flush();

            return $this->redirectToRoute('user_list');
        }
        return $this->render('user/edit.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    /**
     * @Route("/user/login", name="login")
     */
    public function login(Request $request)
    {
        $session = new Session();
        $log = $session->get('email');
        if($log!=null){ return new Response('Poprawnie zalogowano użytkownika: '.$log);}
        $login = new Login();
        $form = $this->createFormBuilder($login)
            ->add('login', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('submit', SubmitType::class, array('label'=>'Zaloguj'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $login = $form->getData();
            $email = $login->getLogin();
            $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['email'=>$email]);
            if($user)
            {
                $session->set('email', $user->getEmail());
                $log = $session->get('email');
                return new Response('Poprawnie zalogowano użytkownika: '.$log);
            } else {
                $form->add('message', LocaleType::class);
            }

        }
        return $this->render('user/login.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    /**
     * @Route("/user/logout", name="logout")
     */
    public function logout()
    {
        $session = new Session();
        $log=$session->get('email');
        if($log!=null)
        {
            $session->remove('email');
            return new Response('Poprawnie wylogowano użytkownika: '.$log);
        }
        else
        {
            return $this->redirectToRoute('login');
        }
    }

}
