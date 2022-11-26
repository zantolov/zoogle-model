<?php

declare(strict_types=1);

namespace Zantolov\Zoogle\Model\Service\Converting;

use Zantolov\Zoogle\Model\Model\Document\Subtitle;
use Zantolov\Zoogle\Model\Model\Google\Paragraph;

/**
 * @internal
 */
final class SubtitleConverter extends AbstractContentElementConverter
{
    /**
     * @return list<Subtitle>
     */
    public function convert(Paragraph $paragraph): array
    {
        $content = $this->getUnformattedParagraphContent($paragraph);

        return [new Subtitle($content)];
    }

    public function supports(Paragraph $paragraph): bool
    {
        return $paragraph->getNamedStyleType() === 'SUBTITLE';
    }
}
