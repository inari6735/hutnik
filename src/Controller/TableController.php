<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\TableRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TableController extends AbstractController
{
    #[Route('/table', name: 'app_table')]
    public function get_table(
        TableRepository $tableRepository,
        UserRepository $userRepository
    ): Response
    {
        $tables = $tableRepository->findAll();
        $usersWithoutTable = $userRepository->findBy(['user_table' => null]);
        $myUserIdentifier = $this->getUser()->getUserIdentifier();
        $user = $userRepository->findOneBy(['email' => $myUserIdentifier]);

        return $this->render('table/new_table.html.twig', [
            'tables' => $tables,
            'myUser' => $user,
            'usersWithoutTable' => $usersWithoutTable
        ]);
    }
}
