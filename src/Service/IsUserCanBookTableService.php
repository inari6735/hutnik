<?php

namespace App\Service;

use App\Entity\Table;
use App\Entity\User;

class IsUserCanBookTableService
{

    public function isUserCanBookTable(User $user, Table $table): bool {
        $sizeCost = (new GetUserSizeCostService())->getUserSizeCost($user);
        $tableSize = $table->getSize();

        if ($tableSize < $sizeCost) {
            return false;
        }
        return true;
    }
}
