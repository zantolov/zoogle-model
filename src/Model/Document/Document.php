<?php

declare(strict_types=1);

namespace Zantolov\Zoogle\Model\Model\Document;

use Cocur\Chain\Chain;

class Document
{
    /**
     * @param list<DocumentElement> $elements
     * @param list<DocumentList> $lists
     * @param list<DocumentObject> $objects
     */
    public function __construct(public string $id, public array $elements, public array $lists, public array $objects)
    {
    }

    /**
     * @return list<DocumentList>
     */
    public function getLists(): array
    {
        return $this->lists;
    }

    /**
     * @return list<DocumentObject>
     */
    public function getObjects(): array
    {
        return $this->objects;
    }

    public function getList(string $listId): ?DocumentList
    {
        /** @var DocumentList|null $result */
        $result = Chain::create($this->lists)
            ->filter(static fn (DocumentList $list): bool => $list->id === $listId)
            ->first() ?: null;

        return $result;
    }

    public function getObject(string $objectId): ?DocumentObject
    {
        /** @var DocumentObject|null $result */
        $result = Chain::create($this->objects)
            ->filter(static fn (DocumentObject $object): bool => $object->id === $objectId)
            ->first() ?: null;

        return $result;
    }

    public function getTitle(): ?Title
    {
        /** @var Title|null $result */
        $result = Chain::create($this->elements)
            ->filter(static fn (DocumentElement $element): bool => $element instanceof Title)
            ->first() ?: null;

        return $result;
    }

    public function getSubtitle(): ?Subtitle
    {
        /** @var Subtitle|null $result */
        $result = Chain::create($this->elements)
            ->filter(static fn (DocumentElement $element): bool => $element instanceof Subtitle)
            ->first() ?: null;

        return $result;
    }

    public function getMetadata(): ?Metadata
    {
        /** @var Metadata|null $result */
        $result = Chain::create($this->elements)
            ->filter(static fn (DocumentElement $element): bool => $element instanceof Metadata)
            ->first() ?: null;

        return $result;
    }

    /**
     * @return array<Image>
     */
    public function getImages(): array
    {
        /** @var array<Image> $result */
        $result = Chain::create($this->elements)
            ->filter(static fn (DocumentElement $element): bool => $element instanceof Image)
            ->values()
            ->array;

        return $result;
    }

    /**
     * @return array<Paragraph>
     */
    public function getParagraphs(): array
    {
        /** @var array<Paragraph> $result */
        $result = Chain::create($this->elements)
            ->filter(static fn (DocumentElement $element): bool => $element instanceof Paragraph)
            ->values()
            ->array;

        return $result;
    }

    public function firstImage(): ?Image
    {
        return $this->getImages()[0] ?? null;
    }

    /**
     * @param list<DocumentElement> $elements
     */
    public function withElements(array $elements): self
    {
        $instance = clone $this;

        /** @var list<DocumentElement> $elements */
        $instance->elements = $elements;

        return $instance;
    }

    public function withoutFirstImage(): self
    {
        $items = Chain::create($this->elements)
            ->filter(fn (DocumentElement $element): bool => $element !== $this->firstImage())
            ->values()
            ->array;

        /** @var list<DocumentElement> $items */
        return $this->withElements($items);
    }
}
