<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

$serializer = new \PhpSerialization\ValinorSerializer\ValinorSerializer();
echo $serializer->unserialize(...);

$serializer = new \PhpSerialization\ValinorSerializer\ValinorSerializer(
    mapper: (new \CuyZ\Valinor\MapperBuilder())->flexible()->mapper(),
);

// Since CuyZ/Valinor can only unserialize, you must pass a SerializeObject implementation.
$serializer = new \PhpSerialization\ValinorSerializer\ValinorSerializer(
    serializeObject: new class implements \PhpSerializer\Serializer\SerializeObject {
        public function serialize(object $object, array $context = []): mixed
        {
            return [];
        }
    }
);
