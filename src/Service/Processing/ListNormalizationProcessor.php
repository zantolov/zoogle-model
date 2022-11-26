<?php

declare(strict_types=1);

namespace Zantolov\Zoogle\Model\Service\Processing;

use Assert\Assertion;
use Zantolov\Zoogle\Model\Model\Document\Document;
use Zantolov\Zoogle\Model\Model\Document\DocumentList;
use Zantolov\Zoogle\Model\Model\Document\ListItem;

/**
 * Groups all the ListItem objects to the DocumentList object, so that it can be handled as a group.
 */
final class ListNormalizationProcessor implements DocumentProcessor
{
    public function process(Document $document): Document
    {
        $lists = [];
        $elements = [];
        foreach ($document->elements as $element) {
            if ($element instanceof ListItem) {
                if (!isset($lists[$element->listId])) {
                    $list = $document->getList($element->listId);
                    Assertion::notNull($list);
                    $lists[$element->listId] = $list;
                    $elements[] = $list;
                }
                $list = $lists[$element->listId];
                $list->add($element);

                continue;
            }

            $elements[] = $element;
        }

        return $document->withElements($elements);
    }

    public function priority(): int
    {
        return 0;
    }
}
