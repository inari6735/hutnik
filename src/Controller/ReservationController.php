<?php

namespace App\Controller;

use App\Repository\ReservationRepository;
use App\Repository\ReservationStatusRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_make_reservation')]
    public function make_reservation(
        Request $request,
        UserRepository $userRepository,
        ReservationRepository $reservationRepository,
        ReservationStatusRepository $reservationStatusRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        $userReservation = $user->getReservations()[0];
        $busyStatus = $reservationStatusRepository->findOneBy(['colour' => 3]);
        $emptyStatus = $reservationStatusRepository->findOneBy(['colour' => 2]);
        $reservation = $reservationRepository->findOneBy(['id' => $request->query->get('reservation_id')]);
        $reservation->removeStatusId($reservation->getStatusId()[0]);
        if(!empty($userReservation)) {
            $previousReservation = $user->getReservations()[0];
            $previousReservation->removeStatusId($busyStatus);
            $previousReservation->addStatusId($emptyStatus);
            $user->removeReservation($userReservation);
            $entityManager->persist($user);
            $entityManager->persist($previousReservation);
            $entityManager->flush();
        }
        $reservation->addUserId($user);
        $reservation->addStatusId($busyStatus);

        $entityManager->flush($reservation);
        $entityManager->persist($reservation);

        return $this->redirectToRoute('app_table');
    }
}
