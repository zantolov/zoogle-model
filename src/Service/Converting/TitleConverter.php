<?php

declare(strict_types=1);

namespace Zantolov\Zoogle\Model\Service\Converting;

use Zantolov\Zoogle\Model\Model\Document\Title;
use Zantolov\Zoogle\Model\Model\Google\Paragraph;

/**
 * @internal
 */
final class TitleConverter extends AbstractContentElementConverter
{
    /**
     * @return list<Title>
     */
    public function convert(Paragraph $paragraph): array
    {
        $content = $this->getUnformattedParagraphContent($paragraph);

        return [new Title($content)];
    }

    public function supports(Paragraph $paragraph): bool
    {
        return $paragraph->getNamedStyleType() === 'TITLE';
    }
}
