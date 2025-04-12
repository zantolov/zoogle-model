<?php

declare(strict_types=1);

namespace Zantolov\Zoogle\Model\Service\Converting;

use Zantolov\Zoogle\Model\Model\Document\DocumentElement;
use Zantolov\Zoogle\Model\Model\Document\ListItem;
use Zantolov\Zoogle\Model\Model\Document\Paragraph;
use Zantolov\Zoogle\Model\Model\Document\Text;
use Zantolov\Zoogle\Model\Model\Google\Paragraph as GoogleParagraph;
use Zantolov\Zoogle\Model\Model\Google\ParagraphElement;
use Zantolov\Zoogle\Model\Model\Google\TextRun;

/**
 * @internal
 */
final class ContentConverter extends AbstractContentElementConverter
{
    /**
     * @return list<DocumentElement>
     */
    public function convert(GoogleParagraph $paragraph): array
    {
        $listId = null;
        $nestingLevel = null;
        $bullet = $paragraph->getBullet();

        if ($bullet !== null) {
            $listId = $bullet->getListId();
            $nestingLevel = $bullet->getNestingLevel();
        }

        $paragraphElements = $this->convertParagraphElements($paragraph->getElements());
        if (empty($paragraphElements)) {
            throw new \RuntimeException('Empty result set after conversion. Tweak the supports method');
        }

        /** @var list<Text> $paragraphElements */
        $paragraphElements = array_filter($paragraphElements);

        // If the paragraph defines a list, wrap all the content in a ListItem that will later be joined in a list.
        if ($listId !== null) {
            return [new ListItem($listId, $paragraphElements, $nestingLevel ?? 1)];
        }

        return [new Paragraph($paragraphElements)];
    }

    public function supports(GoogleParagraph $paragraph): bool
    {
        return $paragraph->getNamedStyleType() === 'NORMAL_TEXT'
            && $this->convertParagraphElements($paragraph->getElements()) !== [];
    }

    /**
     * @param ParagraphElement[] $elements
     *
     * @return array<int, Text|null>
     */
    private function convertParagraphElements(array $elements): array
    {
        $paragraphElements = array_map(
            fn (ParagraphElement $element): ?Text => $this->convertParagraphElement($element),
            $elements,
        );

        return array_filter($paragraphElements);
    }

    private function convertParagraphElement(ParagraphElement $element): ?Text
    {
        // Skip empty content
        $textRun = $element->getTextRun();
        $content = $textRun?->getContent();
        if (!$textRun instanceof TextRun || $content === null || empty(mb_trim($content))) {
            return null;
        }

        $style = $textRun->getTextStyle();

        return new Text(
            $content,
            $style?->getBold() ?? false,
            $style?->getItalic() ?? false,
            $style?->getUnderline() ?? false,
            $style?->getLinkUrl() ?? null,
        );
    }
}
