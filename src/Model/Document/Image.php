<?php

declare(strict_types=1);

namespace Zantolov\Zoogle\Model\Model\Document;

final class Image implements DocumentElement
{
    public function __construct(public string $id, public string $src, public ?string $alt, public ?string $description)
    {
    }

    public function toString(): string
    {
        return $this->src;
    }

    public function withSrc(string $src): self
    {
        $instance = clone $this;
        $instance->src = $src;

        return $instance;
    }
}
