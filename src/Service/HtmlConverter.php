<?php

declare(strict_types=1);

namespace Zantolov\Zoogle\Model\Service;

use Zantolov\Zoogle\Model\Model\Document\Document;
use Zantolov\Zoogle\Model\Model\Document\DocumentElement;
use Zantolov\Zoogle\Model\Model\Document\DocumentList;
use Zantolov\Zoogle\Model\Model\Document\Heading;
use Zantolov\Zoogle\Model\Model\Document\Image;
use Zantolov\Zoogle\Model\Model\Document\ListItem;
use Zantolov\Zoogle\Model\Model\Document\Metadata;
use Zantolov\Zoogle\Model\Model\Document\Paragraph;
use Zantolov\Zoogle\Model\Model\Document\Subtitle;
use Zantolov\Zoogle\Model\Model\Document\Text;
use Zantolov\Zoogle\Model\Model\Document\Title;

final class HtmlConverter
{
    public function convert(Document $document): string
    {
        $string = '';
        foreach ($document->elements as $item) {
            $string .= $this->renderItem($item);
        }

        return $string;
    }

    public function renderItem(DocumentElement $item): string
    {
        if ($item instanceof Heading) {
            return '<h'.$item->level.'>'.$item->value.'</h'.$item->level.'>';
        }

        if ($item instanceof Text) {
            $value = $item->value;
            $value = mb_rtrim($value, "\v");
            $value = str_replace("\v", '<br/>', $value);

            if ($item->link !== null && $item->link !== '' && $item->link !== '0') {
                return '<a href="'.$item->link.'">'.$value.'</a>';
            }

            if ($item->italic) {
                $value = '<i>'.$value.'</i>';
            }

            if ($item->underline) {
                $value = '<u>'.$value.'</u>';
            }

            if ($item->bold) {
                $value = '<b>'.$value.'</b>';
            }

            return $value;
        }

        if ($item instanceof Paragraph) {
            $content = array_reduce(
                $item->texts,
                fn (string $carry, Text $text): string => $carry.$this->renderItem($text),
                '',
            );

            return '<p>'.$content.'</p>';
        }

        if ($item instanceof ListItem) {
            $content = array_reduce(
                $item->texts,
                fn (string $carry, Text $text): string => $carry.$this->renderItem($text),
                '',
            );

            return '<li>'.$content.'</li>';
        }

        if ($item instanceof DocumentList) {
            $listItems = array_map(fn (ListItem $item): string => $this->renderItem($item), $item->items);
            $listItems = implode("\n", $listItems);

            return match ($item->type) {
                DocumentList::TYPE_ORDERED => '<ol>'.$listItems.'</ol>',
                DocumentList::TYPE_UNORDERED => '<ul>'.$listItems.'</ul>',
                default => throw new \InvalidArgumentException('Unsupported list type given: '.$item->type)
            };
        }

        if ($item instanceof Image) {
            return '<img src="'.$item->src.'" alt="'.$item->alt.'" data-description="'.$item->description.'"/>';
        }

        // Skip these elements from the content
        if ($item instanceof Metadata) {
            return '';
        }

        if ($item instanceof Title) {
            return '';
        }

        if ($item instanceof Subtitle) {
            return '';
        }

        throw new \InvalidArgumentException('Unsupported element given: '.$item::class);
    }
}
