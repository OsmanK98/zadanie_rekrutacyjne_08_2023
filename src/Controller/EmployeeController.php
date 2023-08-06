<?php

namespace App\Controller;

use App\Dto\Input\CreateEmployeeDto;
use App\Dto\Input\EmployeeListQueryDto;
use App\Dto\Input\UpdateEmployeeDto;
use App\Dto\Output\EmployeeCreatedOutput;
use App\Dto\Output\EmployeeListOutput;
use App\Dto\Output\EmployeeListPaginationOutput;
use App\Dto\Output\EmployeeShowOutput;
use App\Dto\Output\EmployeeUpdatedOutput;
use App\Entity\Employee;
use App\Service\EmployeeCreator;
use App\Service\EmployeeProvider;
use App\Service\EmployeeUpdater;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[AsController]
#[Route(path: '/api/employees')]
class EmployeeController
{
    public function __construct(
        private EmployeeCreator $employeeCreator,
        private EmployeeUpdater $employeeUpdater,
        private EmployeeProvider $employeeProvider,
    ) {
    }

    #[Route('', methods: ['POST'])]
    #[OA\Tag(name: 'Employee')]
    #[OA\RequestBody(
        description: "Data for creating a new employee",
        required: true,
        content: new OA\JsonContent(ref: new Model(type: CreateEmployeeDto::class))
    )]
    #[OA\Response(
        response: 201,
        description: 'Returns the employee data after created',
        content: new OA\JsonContent(ref: new Model(type: EmployeeCreatedOutput::class))
    )]
    //TODO Documentation for all response error codes
    public function create(
        CreateEmployeeDto $dto
    ): Response {
        $createdEmployeeDto = $this->employeeCreator->add($dto);

        return new JsonResponse($createdEmployeeDto, Response::HTTP_CREATED);
    }

    #[Route('/{id}', methods: ['POST'])]
    #[OA\Tag(name: 'Employee')]
    #[OA\RequestBody(
        description: "Data for creating a new employee",
        required: true,
        content: new OA\JsonContent(ref: new Model(type: UpdateEmployeeDto::class))
    )]
    #[OA\Response(
        response: 201,
        description: 'Returns details about Employee',
        content: new OA\JsonContent(ref: new Model(type: EmployeeUpdatedOutput::class))
    )]
    //TODO Documentation for all response error codes
    public function update(
        string $id,
        UpdateEmployeeDto $dto
    ): Response {
        $updatedEmployeeDto = $this->employeeUpdater->edit(Uuid::fromString($id), $dto);

        return new JsonResponse($updatedEmployeeDto, Response::HTTP_OK);
    }

    #[Route('/{id}', methods: ['GET'])]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'OK',
        content: new OA\JsonContent(ref: new Model(type: EmployeeShowOutput::class))
    )]
    #[OA\Tag(name: 'Employee')]
    public function show(
        string $id
    ): Response {
        return new JsonResponse(
            $this->employeeProvider->getOne(Uuid::fromString($id))
        );
    }

    #[Route('', methods: ['GET'])]
    #[OA\Parameter(
        name: 'page',
        description: 'Page number',
        in: 'query',
        schema: new OA\Schema(type: 'integer'),
    )]
    #[OA\Parameter(
        name: 'limit',
        description: 'Number of items per page',
        in: 'query',
        schema: new OA\Schema(type: 'integer'),
    )]
    #[OA\Parameter(
        name: 'searchValue',
        description: 'Search value',
        in: 'query',
        schema: new OA\Schema(type: 'string'),
    )]
    #[OA\Parameter(
        name: 'sortBy',
        description: 'Field to sort by',
        in: 'query',
        schema: new OA\Schema(type: 'string'),
    )]
    #[OA\Parameter(
        name: 'sortOrder',
        description: 'Sort order (\'asc\' or \'desc\')',
        in: 'query',
        schema: new OA\Schema(type: 'string'),
    )]
    #[OA\Response(
        response: Response::HTTP_OK,
        description: 'OK',
        content: new OA\JsonContent(ref: new Model(type: EmployeeListPaginationOutput::class))
    )]
    #[OA\Tag(name: 'Employee')]
    public function list(
        Request $request,
    ): Response {
        return new JsonResponse(
            $this->employeeProvider->getAll(
                new EmployeeListQueryDto(
                    $request->query->get('page'),
                    $request->query->get('limit'),
                    $request->query->get('searchValue'),
                    $request->query->get('sortBy'),
                    $request->query->get('sortOrder'),
                )
            )
        );
    }
}
