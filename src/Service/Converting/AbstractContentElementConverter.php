<?php

declare(strict_types=1);

namespace Zantolov\Zoogle\Model\Service\Converting;

use Zantolov\Zoogle\Model\Model\Google\Paragraph;
use Zantolov\Zoogle\Model\Model\Google\ParagraphElement;
use Zantolov\Zoogle\Model\Model\Google\TextRun;

/**
 * @internal
 */
abstract class AbstractContentElementConverter implements ElementConverter
{
    /**
     * Joins all content in a given paragraph without any formatting.
     */
    protected function getUnformattedParagraphContent(Paragraph $paragraph): string
    {
        return array_reduce(
            $paragraph->getElements(),
            static function (
                string $carry,
                ParagraphElement $element,
            ): string {
                /** @var TextRun|null $textRun */
                $textRun = $element->getTextRun();
                $content = $textRun?->getContent();

                return $carry.mb_trim(
                    in_array($content, [null, '', '0'], true)
                        ? ''
                        : $content,
                );
            },
            '',
        );
    }

    protected function getFormattedParagraphContent(Paragraph $paragraph): string
    {
        return array_reduce(
            $paragraph->getElements(),
            fn (
                string $carry,
                ParagraphElement $element,
            ): string => $carry.$this->getFormattedParagraphElementContent($element),
            '',
        );
    }

    private function getFormattedParagraphElementContent(ParagraphElement $element): string
    {
        $textRun = $element->getTextRun();
        if (!$textRun instanceof TextRun) {
            return '';
        }

        $content = $textRun->getContent();
        if ($content === null || in_array(mb_trim($content), ['', '0'], true)) {
            return '';
        }

        $url = $textRun->getTextStyle()?->getLinkUrl();
        if ($url !== null) {
            return '<a href="'.$url.'">'.$content.'</a>';
        }

        // @todo bold
        // @todo italic
        // @todo underline

        return $content;
    }
}
