<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToMany(targetEntity: Table::class, inversedBy: 'reservations')]
    private $table_id;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'reservations')]
    private $user_id;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $position;

    #[ORM\ManyToMany(targetEntity: ReservationStatus::class)]
    private $status_id;

    public function __construct()
    {
        $this->table_id = new ArrayCollection();
        $this->user_id = new ArrayCollection();
        $this->status_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Table>
     */
    public function getTableId(): Collection
    {
        return $this->table_id;
    }

    public function addTableId(Table $tableId): self
    {
        if (!$this->table_id->contains($tableId)) {
            $this->table_id[] = $tableId;
        }

        return $this;
    }

    public function removeTableId(Table $tableId): self
    {
        $this->table_id->removeElement($tableId);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUserId(): Collection
    {
        return $this->user_id;
    }

    public function addUserId(User $userId): self
    {
        if (!$this->user_id->contains($userId)) {
            $this->user_id[] = $userId;
        }

        return $this;
    }

    public function removeUserId(User $userId): self
    {
        $this->user_id->removeElement($userId);

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(?int $position): self
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @return Collection<int, ReservationStatus>
     */
    public function getStatusId(): Collection
    {
        return $this->status_id;
    }

    public function addStatusId(ReservationStatus $statusId): self
    {
        if (!$this->status_id->contains($statusId)) {
            $this->status_id[] = $statusId;
        }

        return $this;
    }

    public function removeStatusId(ReservationStatus $statusId): self
    {
        $this->status_id->removeElement($statusId);

        return $this;
    }
}
