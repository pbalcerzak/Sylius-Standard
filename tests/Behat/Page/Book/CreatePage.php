<?php

declare(strict_types=1);

namespace App\Tests\Behat\Page\Book;

use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;

final class CreatePage extends BaseCreatePage implements CreatePageInterface
{

    public function fillWithTitle($title): string
    {
        $this->getDocument()->fillField('Title', $title);

        return $title;
    }

    public function add(): void
    {
        $this->getDocument()->pressButton('Create');
    }
}
