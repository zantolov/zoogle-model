<?php

declare(strict_types=1);

namespace Zantolov\Zoogle\Model\Model\Google;

use Google\Service\Docs\TextRun as GoogleTextRun;

/** @psalm-immutable */
final readonly class TextRun
{
    public function __construct(private GoogleTextRun $decorated)
    {
    }

    public function getContent(): ?string
    {
        /** @var string|null $content */
        return $this->decorated->getContent();
    }

    public function getTextStyle(): ?TextStyle
    {
        /** @var \Google\Service\Docs\TextStyle|null $textStyle */
        $textStyle = $this->decorated->getTextStyle();

        if ($textStyle !== null) {
            return new TextStyle($this->decorated->getTextStyle());
        }

        return null;
    }
}
