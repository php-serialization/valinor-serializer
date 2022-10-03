<?php

declare(strict_types=1);

namespace PhpSerialization\ValinorSerializer\Tests;

/**
 * @template-implements \IteratorAggregate<string, non-empty-string>
 */
final class A implements \IteratorAggregate
{
    /**
     * @psalm-param non-empty-string $name
     */
    public function __construct(
        public readonly string $name,
    ) {
    }

    /**
     * @return \Traversable<string, non-empty-string>
     */
    public function getIterator(): \Traversable
    {
        yield 'name' => $this->name;
    }
}
