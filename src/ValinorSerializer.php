<?php

declare(strict_types=1);

namespace PhpSerialization\ValinorSerializer;

use CuyZ\Valinor\Mapper\TreeMapper;
use CuyZ\Valinor\MapperBuilder;
use PhpSerializer\Serializer\SerializeObject;
use PhpSerializer\Serializer\Serializer;
use PhpSerializer\Serializer\UnableToSerializeObject;
use PhpSerializer\Serializer\UnableToUnserializeObject;

final class ValinorSerializer implements Serializer
{
    private readonly TreeMapper $mapper;

    public function __construct(
        ?TreeMapper $mapper = null,
        private readonly ?SerializeObject $serializeObject = null,
    ) {
        $this->mapper = $mapper ?: (new MapperBuilder())->mapper();
    }

    /**
     * @param object $object
     * @param array<string, mixed> $context
     *
     * @return mixed
     *
     * @throws UnableToSerializeObject
     */
    public function serialize(object $object, array $context = []): mixed
    {
        if (null === $this->serializeObject) {
            throw new UnableToSerializeObject('SerializeObject not set.');
        }

        try {
            /** @var array */
            return $this->serializeObject->serialize($object);
        } catch (\Throwable $e) {
            throw new UnableToSerializeObject($e->getMessage(), (int)$e->getCode(), $e);
        }
    }

    /**
     * @template T of object
     *
     * @param class-string<T> $class
     * @param array $payload
     * @param array<string, mixed> $context
     *
     * @return T
     *
     * @throws UnableToUnserializeObject
     */
    public function unserialize(string $class, array $payload, array $context = []): object
    {
        try {
            /** @var T */
            return $this->mapper->map($class, $payload);
        } catch (\Throwable $e) {
            throw new UnableToUnserializeObject($e->getMessage(), (int)$e->getCode(), $e);
        }
    }
}
