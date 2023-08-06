<?php

namespace App\Repository;

use App\Entity\Employee;
use App\Entity\Gender;
use App\Entity\Pesel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @method Employee|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employee|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employee[]    findAll()
 * @method Employee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployeeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employee::class);
    }

    public function checkUniqueOfField(string $field, string $value, Uuid $excludedId = null)
    {
        $qb = $this->createQueryBuilder('e')
            ->where("e.$field = :value")
            ->setParameter('value', $value);

        if ($excludedId) {
            $qb->andWhere('e.id != :excludedId');
            $qb->setParameter('excludedId', $excludedId->toBinary());
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function getOne(Uuid $id)
    {
        $qb = $this->createQueryBuilder('e')
            ->where('e.id = :id')
            ->setParameter('id', $id->toBinary());

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function save(Employee $employee): Employee
    {
        $this->_em->persist($employee);
        $this->_em->flush();

        return $employee;
    }

    public function getAllQuery($searchValue = null, $sortBy = null, $sortOrder = 'ASC'): Query
    {
        $qb = $this->createQueryBuilder('e')
            ->join(Gender::class, 'g');

        if ($searchValue !== null) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('e.firstName', ':searchValue'),
                    $qb->expr()->like('e.lastName', ':searchValue'),
                    $qb->expr()->like('e.pesel.pesel', ':searchValue'),
                    $qb->expr()->like('g.name', ':searchValue'),
                    $qb->expr()->like('e.birthdate', ':searchValue'),
                )
            )->setParameter('searchValue', '%' . $searchValue . '%');
        }

        if ($sortBy !== null) {
            switch ($sortBy) {
                case 'firstName':
                case 'lastName':
                case 'email':
                case 'birthdate':
                    $qb->orderBy('e.' . $sortBy, $sortOrder);
                    break;
                case 'pesel':
                    $qb->orderBy('e.pesel.' . $sortBy, $sortOrder);
                    break;
                case 'gender':
                    $qb->orderBy('g.' . $sortBy, $sortOrder);
                    break;
            }
        }

        return $qb->getQuery();
    }
}
