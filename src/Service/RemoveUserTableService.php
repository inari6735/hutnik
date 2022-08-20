<?php

namespace App\Service;

use App\Entity\Table;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class RemoveUserTableService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function removeUserTable(User $user, Table $table): void {
        $tableSize = $table->getSize();
        $sizeCost = (new GetUserSizeCostService())->getUserSizeCost($user);
        $newSize = $tableSize + $sizeCost;

        $this->entityManager->beginTransaction();
        try {
            $table->setSize($newSize);
            $user->setUserTable(null);
            $this->entityManager->persist($user);
            $this->entityManager->persist($table);
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
        } catch (Exception $e) {
            $this->entityManager->getConnection()->rollBack();
            throw $e;
        }
        $this->entityManager->close();
    }
}
