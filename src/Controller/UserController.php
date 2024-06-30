<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\UserService;

class UserController extends AbstractController
{
    private UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user/create', name: 'user_create', methods: ['GET', 'POST'])]
    public function createUser(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $encodedPassword = $this->userService->encodePassword($user, $user->getPassword());
            $user->setPassword($encodedPassword);
    
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
    
            if ($user->getRoles() && $user->getRoles()->getName() === 'customer') {
                $this->addFlash('success', 'Customer account created. Please complete your profile.');
                return $this->redirectToRoute('customer_new', ['userId' => $user->getId()]);
            } else if ($user->getRoles() && $user->getRoles()->getName() === 'accountant') {
                $this->addFlash('success', 'Accountant account created. Welcome!');
                return $this->redirectToRoute('home');
            }
    
            $this->addFlash('success', 'User created successfully');
            return $this->redirectToRoute('app_user');
        }
    
        return $this->render('user/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/user/{id}', name: 'user_delete', methods: ['POST'])]
    public function deleteUser(Request $request, User $user): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur supprimé avec succès');
        return $this->redirectToRoute('app_user');
    }

    #[Route('/user/update/{id}', name: 'user_update', methods: ['GET', 'POST'])]
    public function updateUser(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('password')->getData()) {
                $encodedPassword = $this->userService->encodePassword($user, $user->getPassword());
                $user->setPassword($encodedPassword);
            }
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Utilisateur modifié');
            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
