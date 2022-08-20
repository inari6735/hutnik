<?php

namespace App\Service;

use App\Entity\Table;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class GetUserSizeCostService
{
    public function getUserSizeCost(User $user): int {
        $sizeCost = 1;

        if($user->isWithPerson()) {
            $sizeCost = 2;
        }

        return $sizeCost;
    }

    public function getTableSizeAfterUserBook(User $user, Table $table): int {
        $sizeCost = $this->getUserSizeCost($user);
        $tableSize = $table->getSize();
        $newSize = $tableSize - $sizeCost;

        return $newSize;
    }
}
