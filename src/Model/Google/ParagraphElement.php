<?php

declare(strict_types=1);

namespace Zantolov\Zoogle\Model\Model\Google;

use Google\Service\Docs\InlineObjectElement;
use Google\Service\Docs\ParagraphElement as GoogleParagraphElement;

/** @psalm-immutable */
final readonly class ParagraphElement
{
    public function __construct(private GoogleParagraphElement $decorated)
    {
    }

    public function getTextRun(): ?TextRun
    {
        /** @var \Google\Service\Docs\TextRun|null $textRun */
        $textRun = $this->decorated->getTextRun();

        if ($textRun !== null) {
            return new TextRun($textRun);
        }

        return null;
    }

    /** @phpstan-ignore-next-line */
    public function getInlineObjectElement(): ?InlineObjectElement
    {
        return $this->decorated->getInlineObjectElement();
    }
}
