<?php

declare(strict_types=1);

namespace Zantolov\Zoogle\Model\Service\Processing;

use Assert\Assertion;
use Google\Service\Docs\Document as GoogleDocument;
use Google\Service\Docs\InlineObject as GoogleInlineObject;
use Zantolov\Zoogle\Model\Model\Document\Document;
use Zantolov\Zoogle\Model\Model\Document\DocumentObject;
use Zantolov\Zoogle\Model\Model\Document\Image;
use Zantolov\Zoogle\Model\Model\Document\InlineObject;

final class ObjectNormalizationProcessor implements DocumentProcessor
{
    public function process(Document $document): Document
    {
        $elements = [];
        foreach ($document->elements as $element) {
            if ($element instanceof InlineObject) {
                $object = $document->getObject($element->id);
                Assertion::notNull($object);
                if ($object->type === DocumentObject::TYPE_IMAGE) {
                    /** @phpstan-ignore cast.string */
                    $src = isset($object->properties['src']) ? (string) $object->properties['src'] : '';

                    /** @phpstan-ignore cast.string */
                    $title = isset($object->properties['title']) ? (string) $object->properties['title'] : null;

                    /** @phpstan-ignore cast.string */
                    $description = isset($object->properties['description']) ? (string) $object->properties['description'] : null;
                    $element = new Image($object->id, $src, $title, $description);
                }
            }

            $elements[] = $element;
        }

        return $document->withElements($elements);
    }

    public function convertInlineObjectToImage(GoogleDocument $document, InlineObject $object): ?Image
    {
        $objects = $document->getInlineObjects();
        Assertion::isArray($objects);
        Assertion::allIsInstanceOf($objects, GoogleInlineObject::class);

        foreach ($objects as $id => $documentObject) {
            if ($id === $object->id) {
                $embeddedObject = $documentObject->getInlineObjectProperties()->getEmbeddedObject();

                /** @var string|null $imageSrc */
                $imageSrc = $embeddedObject->getImageProperties()->getContentUri();
                $alt = $embeddedObject->getTitle();
                $description = $embeddedObject->getDescription();

                // @todo add support for cropped content
                // @todo add support for drawings

                if ($imageSrc !== null) {
                    return new Image($documentObject->getObjectId(), (string) $imageSrc, (string) $alt, (string) $description);
                }
            }
        }

        return null;
    }

    public function priority(): int
    {
        return 0;
    }
}
