<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function get_user(): Response
    {
        return $this->render('user/user.html.twig', [
            'user' => $this->getUser()
        ]);
    }

    #[Route('/user/update', name: 'app_use_update')]
    public function update_user(
        Request $request,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ): Response
    {
        $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $user->setFirstName($request->query->get('first_name'));
        $user->setLastName($request->query->get('last_name'));
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_user');
    }
}
