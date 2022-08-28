<?php

namespace App\Controller;

use App\Repository\TableRepository;
use App\Repository\UserRepository;
use App\Service\GetUserSizeCostService;
use App\Service\IsUserCanBookTableService;
use App\Service\RemoveUserTableService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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

    #[Route('/user/table/{tableId}', name: 'app_book_table')]
    public function add_table_to_user(
        int $tableId,
        UserRepository $userRepository,
        TableRepository $tableRepository,
        EntityManagerInterface $entityManager,
        IsUserCanBookTableService $bookTableService,
        GetUserSizeCostService $costService,
        RemoveUserTableService $removeUserTableService,
        GetUserSizeCostService $getUserSizeCostService
    ): Void
    {
        $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $table = $tableRepository->findOneBy(['id' => $tableId]);
        $userTable = $user->getUserTable();
        $sizeCost = $getUserSizeCostService->getUserSizeCost($user);

        if ($bookTableService->isUserCanBookTable($user, $table)) {
            $newSize = $costService->getTableSizeAfterUserBook($user, $table);

            $entityManager->beginTransaction();
            try {
                if($userTable) {
                    $tableSize = $userTable->getSize();
                    $userTable->setSize($tableSize + $sizeCost);
                    $entityManager->persist($userTable);
                }
                $user->setUserTable($table);
                $table->setSize($newSize);
                $entityManager->persist($user);
                $entityManager->persist($table);
                $entityManager->flush();
                $entityManager->getConnection()->commit();
            } catch (Exception $e) {
                $entityManager->getConnection()->rollBack();
                throw $e;
            }
            $entityManager->close();
        }
    }


    #[Route('/admin/table/{tableId}/user/{userId}', name: 'app_manage_table')]
    public function manage_user_table(
        int $tableId,
        int $userId,
        UserRepository $userRepository,
        TableRepository $tableRepository,
        EntityManagerInterface $entityManager,
        IsUserCanBookTableService $bookTableService,
        GetUserSizeCostService $costService,
        GetUserSizeCostService $getUserSizeCostService
    ): Void
    {
        $user = $userRepository->findOneBy(['id' => $userId]);
        $table = $tableRepository->findOneBy(['id' => $tableId]);
        $userTable = $user->getUserTable();
        $sizeCost = $getUserSizeCostService->getUserSizeCost($user);

        $newSize = $costService->getTableSizeAfterUserBook($user, $table);

        $entityManager->beginTransaction();
        try {
            if(!$userTable) {
                $user->setUserTable($table);
                $table->setSize($newSize);
                $entityManager->persist($user);
                $entityManager->persist($table);
                $entityManager->flush();
                $entityManager->getConnection()->commit();
            }

            if($userTable->getId() != $table->getId()) {
                $tableSize = $userTable->getSize();
                $userTable->setSize($tableSize + $sizeCost);
                $entityManager->persist($userTable);
                $user->setUserTable($table);
                $table->setSize($newSize);
                $entityManager->persist($user);
                $entityManager->persist($table);
                $entityManager->flush();
                $entityManager->getConnection()->commit();
            }
        } catch (Exception $e) {
            $entityManager->getConnection()->rollBack();
            throw $e;
        }
        $entityManager->close();
    }

    #[Route('/user/remove/table/{tableId}', name: 'app_remove_user_table')]
    public function remove_user_table(
        int $tableId,
        UserRepository $userRepository,
        GetUserSizeCostService $costService,
        TableRepository $tableRepository,
        RemoveUserTableService $removeUserTableService
    ): Void
    {
        $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $table = $tableRepository->findOneBy(['id' => $tableId]);

        $removeUserTableService->removeUserTable($user, $table);
    }

    #[Route('/admin/remove/user/{userId}', name: 'app_remove_admin_table')]
    public function remove_admin_table(
        int $userId,
        UserRepository $userRepository,
        TableRepository $tableRepository,
        RemoveUserTableService $removeUserTableService
    ): Response
    {
        $user = $userRepository->findOneBy(['id' => $userId]);
        $table = $user->getUserTable();

        $removeUserTableService->removeUserTable($user, $table);

        return $this->json([]);
    }
}
