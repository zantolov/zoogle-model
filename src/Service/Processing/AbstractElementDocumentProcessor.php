<?php

declare(strict_types=1);

namespace Zantolov\Zoogle\Model\Service\Processing;

use Zantolov\Zoogle\Model\Model\Document\Document;
use Zantolov\Zoogle\Model\Model\Document\DocumentElement;

abstract class AbstractElementDocumentProcessor implements DocumentProcessor
{
    abstract protected function supports(DocumentElement $element): bool;

    abstract protected function processElement(DocumentElement $element, Document $document): DocumentElement;

    public function process(Document $document): Document
    {
        $elements = [];
        foreach ($document->elements as $element) {
            if ($this->supports($element)) {
                $element = $this->processElement($element, $document);
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
