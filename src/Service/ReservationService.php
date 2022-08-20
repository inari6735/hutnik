<?php

namespace App\Service;

use Symfony\Component\Security\Core\User\UserInterface;

class ReservationService
{
    public function ifUserHasReservations(UserInterface $user): bool {
        if (!empty($userReservation)) {
            return true;
        }
        return false;
    }
}
