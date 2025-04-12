<?php

declare(strict_types=1);

namespace Zantolov\Zoogle\Model\Model\Document;

final class Subtitle implements \Stringable, DocumentElement
{
    public function __construct(public string $value)
    {
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return $this->value;
    }
}
