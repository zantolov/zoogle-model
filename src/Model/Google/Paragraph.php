<?php

declare(strict_types=1);

namespace Zantolov\Zoogle\Model\Model\Google;

use Google\Service\Docs\Paragraph as GoogleParagraph;
use Google\Service\Docs\ParagraphElement as GoogleParagraphElement;
use Google\Service\Docs\ParagraphStyle;

final readonly class Paragraph
{
    public function __construct(private GoogleParagraph $decorated)
    {
    }

    public function getBullet(): ?Bullet
    {
        /** @var \Google\Service\Docs\Bullet|null $bullet */
        $bullet = $this->decorated->getBullet();

        if ($bullet !== null) {
            return new Bullet($this->decorated->getBullet());
        }

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

        /** @var list<ParagraphElement> */
        return array_map(
            static fn (GoogleParagraphElement $element): ParagraphElement => new ParagraphElement($element),
            $this->decorated->getElements(),
        );
    }

    public function getNamedStyleType(): ?string
    {
        /** @var ParagraphStyle|null $style */
        $style = $this->decorated->getParagraphStyle();

        if ($style === null) {
            return null;
        }

        return $style->getNamedStyleType();
    }
}
