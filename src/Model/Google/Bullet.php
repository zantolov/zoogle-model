<?php

declare(strict_types=1);

namespace Zantolov\Zoogle\Model\Model\Google;

use Google\Service\Docs\Bullet as GoogleBullet;

final class Bullet
{
    public function __construct(private readonly GoogleBullet $decorated)
    {
    }

    public function getListId(): ?string
    {
        return $this->decorated->getListId();
    }

    public function getNestingLevel(): ?int
    {
        return $this->decorated->getNestingLevel();
    }
}
