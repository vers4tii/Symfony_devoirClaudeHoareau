<?php

namespace App\Repository;

use App\Entity\Character;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Character>
 *
 * @method Character|null find($id, $lockMode = null, $lockVersion = null)
 * @method Character|null findOneBy(array $criteria, array $orderBy = null)
 * @method Character[]    findAll()
 * @method Character[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CharacterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Character::class);
    }

    /**
     * Trouve les personnages par niveau
     */
    public function findByLevel(int $level): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.level = :level')
            ->setParameter('level', $level)
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les personnages par arcane
     */
    public function findByArcana(string $arcana): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.arcana = :arcana')
            ->setParameter('arcana', $arcana)
            ->orderBy('c.level', 'DESC')
            ->getQuery()
            ->getResult();
    }
}