<?php

declare(strict_types=1);

namespace Zantolov\Zoogle\Model\Service\Converting;

use Zantolov\Zoogle\Model\Model\Document\DocumentElement;
use Zantolov\Zoogle\Model\Model\Document\ListItem;
use Zantolov\Zoogle\Model\Model\Document\Paragraph;
use Zantolov\Zoogle\Model\Model\Document\Text;
use Zantolov\Zoogle\Model\Model\Google\Bullet;
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

        if ($bullet instanceof Bullet) {
            $listId = $bullet->getListId();
            $nestingLevel = $bullet->getNestingLevel();
        }

        $paragraphElements = $this->convertParagraphElements($paragraph->getElements());
        if ($paragraphElements === []) {
            throw new \RuntimeException('Empty result set after conversion. Tweak the supports method');
        }

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
     * @return array<Text>
     */
    private function convertParagraphElements(array $elements): array
    {
        $paragraphElements = array_map(
            fn (ParagraphElement $element): ?Text => $this->convertParagraphElement($element),
            $elements,
        );

        /** @var array<Text> */
        return array_filter($paragraphElements);
    }

    private function convertParagraphElement(ParagraphElement $element): ?Text
    {
        // Skip empty content
        $textRun = $element->getTextRun();
        $content = $textRun?->getContent();
        if (!$textRun instanceof TextRun || $content === null || in_array(mb_trim($content), ['', '0'], true)) {
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
