<?php

namespace App\Service;

use App\Entity\Character;
use App\Entity\User;
use App\Repository\CharacterRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service pour la gestion des personnages
 */
class CharacterService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CharacterRepository $characterRepository
    ) {}

    /**
     * Crée un nouveau personnage pour un utilisateur
     */
    public function createCharacter(string $name, string $arcana, User $user): Character
    {
        $character = new Character();
        $character->setName($name)
                 ->setArcana($arcana)
                 ->setUser($user);

        $this->entityManager->persist($character);
        $this->entityManager->flush();

        return $character;
    }

    /**
     * Récupère tous les personnages d'un utilisateur
     */
    public function getUserCharacters(User $user): array
    {
        return $this->characterRepository->findBy(['user' => $user]);
    }

    /**
     * Vérifie si un utilisateur est propriétaire d'un personnage
     */
    public function isCharacterOwner(Character $character, User $user): bool
    {
        return $character->getUser() === $user;
    }

    /**
     * Met à jour le niveau d'un personnage
     */
    public function levelUp(Character $character): void
    {
        $character->setLevel($character->getLevel() + 1);
        $this->entityManager->flush();
    }
}