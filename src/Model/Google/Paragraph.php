<?php

declare(strict_types=1);

namespace Zantolov\Zoogle\Model\Model\Google;

use Google\Service\Docs\Paragraph as GoogleParagraph;
use Google\Service\Docs\ParagraphElement as GoogleParagraphElement;

final class Paragraph
{
    public function __construct(private readonly GoogleParagraph $decorated)
    {
    }

    public function getBullet(): ?Bullet
    {
        if ($this->decorated->getBullet() !== null) {
            return new Bullet($this->decorated->getBullet());
        }

        /** @phpstan-ignore-next-line */
        return null;
    }

    /**
     * @return list<ParagraphElement>
     */
    public function getElements(): array
    {
        if (!$this->decorated->getElements()) {
            return [];
        }

        return array_map(
            static fn (GoogleParagraphElement $element): ParagraphElement => new ParagraphElement($element),
            $this->decorated->getElements()
        );
    }

    public function getNamedStyleType(): ?string
    {
        /** @phpstan-ignore-next-line */
        return $this->decorated->getParagraphStyle()?->getNamedStyleType();
    }
}
