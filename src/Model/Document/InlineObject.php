<?php

declare(strict_types=1);

namespace Zantolov\Zoogle\Model\Model\Document;

/**
 * Inline reference to an object. Can be rendered only from the data stored in DocumentObject on the Document level.
 */
final class InlineObject implements DocumentElement
{
    public function __construct(public string $id)
    {
    }

    public function toString(): string
    {
        return $this->id;
    }
}
