<?php

declare(strict_types=1);

namespace App\Tests\Behat\Page\Book;

use Sylius\Behat\Page\Admin\Crud\CreatePageInterface as BaseCreatePageInterface;

interface CreatePageInterface extends BaseCreatePageInterface
{
    public function fillWithTitle($title): string;

    public function add(): void;
}
