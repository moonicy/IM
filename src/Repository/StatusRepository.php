<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\Laptop;
use App\Entity\Status;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Status|null find($id, $lockMode = null, $lockVersion = null)
 * @method Status|null findOneBy(array $criteria, array $orderBy = null)
 * @method Status[]    findAll()
 * @method Status[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Status::class);
    }

    public function findByFilter(array $filter, $limit = null, $offset = null)
    {
        $builder = $this
            ->createQueryBuilder('s')
            ->orderBy('s.id', 'DESC');

        if (null !== $limit) {
            $builder->setMaxResults($limit);
        }

        if (null !== $offset) {
            $builder->setFirstResult($offset);
        }

        if (isset($filter['status'])) {
            $builder
                ->andWhere('s.status = :status')
                ->setParameter('status', $filter['status']);
        }

        if (isset($filter['dateStart'])) {
            $builder
                ->andWhere('s.dateStart = :dateStart')
                ->setParameter('dateStart', $filter['dateStart']);
        }

        if (isset($filter['dateEnd'])) {
            $builder
                ->andWhere('s.dateEnd = :dateEnd')
                ->setParameter('dateEnd', $filter['dateEnd']);
        }

        if (isset($filter['employee'])) {
            $builder
                ->innerJoin(Employee::class, 'e', 'with', 'e.id = s.employee')
                ->andWhere('e.fio = :fio')
                ->setParameter('fio', $filter['employee']);
        }

        if (isset($filter['laptop']) && is_array($filter['laptop'])) {
            $builder->innerJoin(Laptop::class, 'l', 'with', 'l.id = s.laptop');

            foreach ($filter['laptop'] as $field => $value) {
                if ('firm' === $field) {
                    $builder
                        ->andWhere('l.firm = :firm')
                        ->setParameter('firm', $value);
                }

                if ('number' === $field) {
                    $builder
                        ->andWhere('l.number = :number')
                        ->setParameter('number', $value);
                }
            }
        }

        $now = new DateTime();

        if (!isset($filter['relevant']) || !isset($filter['outdated'])) {

            if (isset($filter['relevant'])) {
                $builder
                    ->andWhere('(s.dateEnd >= :now or s.dateEnd is null)')
                    ->setParameter('now', $now);
            }

            if (isset($filter['outdated'])) {
                $builder
                    ->andWhere('s.dateEnd < :now')
                    ->setParameter('now', $now);
            }
        }

        return $builder
            ->getQuery()
            ->getResult();
    }

    public function findLast($laptop): ?Status
    {
        return $this
            ->createQueryBuilder('s')
            ->innerJoin(Laptop::class, 'l', 'with', 'l.id = s.laptop')
            ->where('l.id = :laptopId')
            ->setParameter('laptopId', $laptop->getId())
            ->orderBy('s.id',  'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return Status[] Returns an array of Status objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Status
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
