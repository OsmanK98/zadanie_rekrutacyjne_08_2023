<?php

namespace App\Infrastructure\ParamConverter;

use App\Dto\Dto;
use App\Infrastructure\Exception\InvalidInputException;
use App\Infrastructure\Exception\ValidationException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\MissingConstructorArgumentsException;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DTOParamConverter implements ParamConverterInterface
{
    public function __construct(
        private SerializerInterface $serializer,
        private ValidatorInterface $validator,
    ) {
    }

    public function apply(Request $request, ParamConverter $configuration)
    {
        try {
            $dto = $this->serializer->deserialize(
                $request->getContent(),
                $configuration->getClass(),
                'json'
            );
        } catch (NotEncodableValueException | MissingConstructorArgumentsException) {
            throw new InvalidInputException('Invalid json');
        }

        $validationErrors = $this->validate($dto);
        if (count($validationErrors) > 0) {
            $e = new ValidationException();
            $e->setValidationErrors($validationErrors);
            throw $e;
        }

        $request->attributes->set($configuration->getName(), $dto);
    }

    public function supports(ParamConverter $configuration): bool
    {
        if (is_subclass_of($configuration->getClass(), Dto::class, true)) {
            return true;
        }

        return false;
    }

    public function validate(Dto $dto): array
    {
        $errors = $this->validator->validate($dto);

        $validationErrors = [];
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $validationErrors[$error->getPropertyPath()] = $error->getMessage();
        }

        return $validationErrors;
    }
}
