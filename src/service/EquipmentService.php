<?php

namespace App\Service;

use App\Entity\Character;
use App\Entity\Equipment;
use App\Repository\EquipmentRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service pour la gestion des équipements
 */
class EquipmentService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private EquipmentRepository $equipmentRepository
    ) {}

    /**
     * Ajoute un équipement à un personnage
     */
    public function addEquipment(Character $character, string $name, string $type, int $power): Equipment
    {
        $equipment = new Equipment();
        $equipment->setName($name)
                 ->setType($type)
                 ->setPower($power)
                 ->setCharacter($character);

        $this->entityManager->persist($equipment);
        $this->entityManager->flush();

        return $equipment;
    }

    /**
     * Récupère tout l'équipement d'un personnage par type
     */
    public function getCharacterEquipmentByType(Character $character, string $type): array
    {
        return $this->equipmentRepository->findBy([
            'character' => $character,
            'type' => $type
        ]);
    }

    /**
     * Calcule la puissance totale de l'équipement d'un personnage
     */
    public function calculateTotalPower(Character $character): int
    {
        return array_reduce(
            $character->getEquipment()->toArray(),
            fn(int $total, Equipment $equipment) => $total + $equipment->getPower(),
            0
        );
    }
}