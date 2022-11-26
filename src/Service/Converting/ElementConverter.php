<?php

declare(strict_types=1);

namespace Zantolov\Zoogle\Model\Service\Converting;

use Zantolov\Zoogle\Model\Model\Document\DocumentElement;
use Zantolov\Zoogle\Model\Model\Google\Paragraph;

/**
 * @internal
 */
interface ElementConverter
{
    /**
     * @return list<DocumentElement>
     */
    public function convert(Paragraph $paragraph): array;

    public function supports(Paragraph $paragraph): bool;
}
