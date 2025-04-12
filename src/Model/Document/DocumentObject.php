<?php

declare(strict_types=1);

namespace Zantolov\Zoogle\Model\Model\Document;

final class DocumentObject implements DocumentElement
{
    public const string TYPE_IMAGE = 'image';

    /**
     * @param array<int|string, mixed> $properties
     */
    public function __construct(public string $id, public string $type, public array $properties)
    {
    }

    public function toString(): string
    {
        return $this->id;
    }
}
