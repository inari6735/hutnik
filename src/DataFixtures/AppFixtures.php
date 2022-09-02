<?php

namespace App\DataFixtures;

use App\Entity\Table;
use App\Repository\TableRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private TableRepository $tableRepository;

    public function __construct(
        TableRepository $tableRepository,
    ) {

        $this->tableRepository = $tableRepository;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 40; $i++) {
            $table = (new Table())->setName('Stół '.$i+1)->setSize(10);
            $manager->persist($table);
        }

        $manager->flush();
    }
}
