<?php

namespace App\Service;

use App\Dto\Input\EmployeeListQueryDto;
use App\Dto\Output\EmployeeListOutput;
use App\Dto\Output\EmployeeListPaginationOutput;
use App\Dto\Output\EmployeeShowOutput;
use App\Entity\Employee;
use App\Infrastructure\Exception\InvalidInputException;
use App\Repository\EmployeeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Uid\Uuid;

class EmployeeProvider
{
    private const DEFAULT_PAGE = 1;
    private const DEFAULT_LIMIT = 10;


    public function __construct(
        private EmployeeRepository $employeeRepository,
        private PaginatorInterface $paginator
    ) {
    }

    public function getOne(Uuid $employeeId): EmployeeShowOutput
    {
        $employee = $this->find($employeeId);
        if (!$employee) {
            throw new InvalidInputException('Employee not exists');
        }

        return new EmployeeShowOutput(
            $employee->getId(),
            $employee->getFirstName(),
            $employee->getLastName(),
            $employee->getEmail(),
            $employee->getGender()->getName(),
            $employee->getPesel()->toString(),
            $employee->getBirthdate()->format('Y-m-d'),
        );
    }

    public function find(Uuid $id): ?Employee
    {
        return $this->employeeRepository->getOne($id);
    }

    public function getAll(EmployeeListQueryDto $dto): EmployeeListPaginationOutput
    {
        $query = $this->employeeRepository->getAllQuery(
            $dto->getSearchValue(),
            $dto->getSortBy(),
            $dto->getSortOrder()
        );

        $result = $this->paginator->paginate(
            $query,
            $dto->getPage() ?? self::DEFAULT_PAGE,
            $dto->getLimit() ?? self::DEFAULT_LIMIT
        );

        $items = array_map(function (Employee $item) {
            return new EmployeeListOutput(
                $item->getId(),
                $item->getFirstName(),
                $item->getLastName(),
                $item->getEmail(),
                $item->getGender()->getName(),
                $item->getPesel()->toString(),
                $item->getBirthdate()->format('Y-m-d'),
            );
        }, $result->getItems());

        return new EmployeeListPaginationOutput(
            $items,
            $result->getItemNumberPerPage(),
            $result->getCurrentPageNumber(),
            $result->getTotalItemCount()
        );
    }
}
