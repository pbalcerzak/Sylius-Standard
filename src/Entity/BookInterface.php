<?php

namespace App\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

interface BookInterface extends ResourceInterface
{
    public function getTitle(): string;

    public function setTitle(string $title): void;
}
