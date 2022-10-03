<?php

declare(strict_types=1);

namespace PhpSerialization\ValinorSerializer\Tests;

use PhpSerialization\ValinorSerializer\ValinorSerializer;
use PhpSerializer\Serializer\SerializeObject;
use PhpSerializer\Serializer\UnableToSerializeObject;
use PhpSerializer\Serializer\UnableToUnserializeObject;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
final class ValinorSerializerTest extends TestCase
{
    public function testUnserialize(): void
    {
        $serializer = new ValinorSerializer();
        $a = $serializer->unserialize(A::class, ['name' => 'kafkiansky']);
        self::assertEquals('kafkiansky', $a->name);

        self::expectException(UnableToUnserializeObject::class);
        $serializer->unserialize(A::class, ['name' => '']);
    }

    public function testSerializeWithoutSerializeObject(): void
    {
        self::expectException(UnableToSerializeObject::class);
        self::expectExceptionMessage('SerializeObject not set.');
        (new ValinorSerializer())->serialize(new A('kafkiansky'));
    }

    public function testSerialize(): void
    {
        $serializer = new ValinorSerializer(serializeObject: new class implements SerializeObject {
            /**
             * {@inheritdoc}
             */
           public function serialize(object $object, array $context = []): array
           {
               \assert($object instanceof \Traversable);
               return \iterator_to_array($object);
           }
        });

        self::assertEquals(['name' => 'kafkiansky'], $serializer->serialize(new A('kafkiansky')));
    }

    public function testSerializeWithException(): void
    {
        $serializer = new ValinorSerializer(serializeObject: new class implements SerializeObject {
            /**
             * {@inheritdoc}
             */
            public function serialize(object $object, array $context = []): array
            {
                throw new \InvalidArgumentException('Invalid object.');
            }
        });

        self::expectException(UnableToSerializeObject::class);
        self::expectExceptionMessage('Invalid object.');
        $serializer->serialize(new A('kafkiansky'));
    }
}
