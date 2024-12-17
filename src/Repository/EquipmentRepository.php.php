<?php

namespace App\Repository;

use App\Entity\Equipment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Equipment>
 *
 * @method Equipment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Equipment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Equipment[]    findAll()
 * @method Equipment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Equipment::class);
 * ResetPasswordRequestRepository constructor.
 *
 * @param ManagerRegistry $registry The registry interface for managing Doctrine ORM entities.
 */


    /**
     * Trouve l'équipement par type et puissance minimale
     */
    public function findByTypeAndMinPower(string $type, int $minPower): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.type = :type')
            ->andWhere('e.power >= :power')
            ->setParameter('type', $type)
            ->setParameter('power', $minPower)
            ->orderBy('e.power', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve l'équipement le plus puissant par type
     */
    public function findMostPowerfulByType(string $type): ?Equipment
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.type = :type')
            ->setParameter('type', $type)
            ->orderBy('e.power', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}