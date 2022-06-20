<?php

namespace App\DataFixtures;

use App\Entity\Reservation;
use App\Entity\ReservationStatus;
use App\Entity\Table;
use App\Repository\ReservationStatusRepository;
use App\Repository\TableRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private TableRepository $tableRepository;
    private ReservationStatusRepository $reservationStatusRepository;

    public function __construct(
        TableRepository $tableRepository,
        ReservationStatusRepository $reservationStatusRepository
    ) {

        $this->tableRepository = $tableRepository;
        $this->reservationStatusRepository = $reservationStatusRepository;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 38; $i++) {
            $table = (new Table())->setName('Stolik '.$i+1)->setSize(10);
            $manager->persist($table);
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();

        $VIP = (new ReservationStatus())->setName('VIP')->setColour(1);
        $empty = (new ReservationStatus())->setName('Wolne')->setColour(2);
        $busy = (new ReservationStatus())->setName('Zajęte')->setColour(3);
        $manager->persist($VIP);
        $manager->persist($empty);
        $manager->persist($busy);

        $manager->flush();

        $tables = $this->tableRepository->findAll();
        $emptyStatus = $this->reservationStatusRepository->findOneBy(['name' => 'Wolne']);
        foreach($tables as $table) {
            for ($i = 0; $i < $table->getSize(); $i++) {
                $reservation = new Reservation();
                $reservation->setPosition($i+1);
                $reservation->addTableId($table);
                $reservation->addStatusId($emptyStatus);
                $manager->persist($reservation);
            }
        }

        $manager->flush();
    }
}
