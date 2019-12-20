<?php

namespace App\Repository;

use App\Entity\Laptop;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Laptop|null find($id, $lockMode = null, $lockVersion = null)
 * @method Laptop|null findOneBy(array $criteria, array $orderBy = null)
 * @method Laptop[]    findAll()
 * @method Laptop[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LaptopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Laptop::class);
    }

    public function findByFilter(array $filter, $limit = null, $offset = null)
    {
        $builder = $this
            ->createQueryBuilder('l')
            ->orderBy('l.id', 'DESC');

        if (null !== $limit) {
            $builder->setMaxResults($limit);
        }

        if (null !== $offset) {
            $builder->setFirstResult($offset);
        }

        if (isset($filter['number'])) {
            $builder
                ->andWhere('l.number = :number')
                ->setParameter('number', $filter['number']);
        }

        if (isset($filter['firm'])) {
            $builder
                ->andWhere('l.firm = :firm')
                ->setParameter('firm', $filter['firm']);
        }

        if (isset($filter['dateBuy'])) {
            $builder
                ->andWhere('l.dateBuy = :dateBuy')
                ->setParameter('dateBuy', $filter['dateBuy']);
        }

        $now = new DateTime();

        if (isset($filter['relevant']) || isset($filter['outdated'])) {
            $collections = $builder
                ->getQuery()
                ->getResult();

            if (empty($collections) || (isset($filter['relevant']) && isset($filter['outdated']))) {
                return $collections;
            }

            return array_filter($collections, function (Laptop $laptop) use ($filter, $now) {
                /** @var DateTime $datetime */
                $datetime = clone $laptop->getDateBuy();
                $datetime->modify($laptop->getInterval()->format('+ %d years'));

                if (isset($filter['relevant'])) {
                    return $datetime > $now;
                }

                if (isset($filter['outdated'])) {
                    /** @var DateTime $datetime */
                    return $datetime < $now;
                }
            });
        }

        return $builder
            ->getQuery()
            ->getResult();
    }
}
