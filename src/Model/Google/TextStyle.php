<?php

declare(strict_types=1);

namespace Zantolov\Zoogle\Model\Model\Google;

use Google\Service\Docs\Link;
use Google\Service\Docs\TextStyle as GoogleTextStyle;

/** @psalm-immutable */
final readonly class TextStyle
{
    public function __construct(private GoogleTextStyle $decorated)
    {
    }

    public function getBold(): bool
    {
        return $this->decorated->getBold() ?: false;
    }

    public function getItalic(): bool
    {
        return $this->decorated->getItalic() ?: false;
    }

    public function getUnderline(): bool
    {
        return $this->decorated->getUnderline() ?: false;
    }

    public function getLinkUrl(): ?string
    {
        /** @var Link|null $link */
        $link = $this->decorated->getLink();

        /** @var string|null */
        return $link?->getUrl();
    }
}
