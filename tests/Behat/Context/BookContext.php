<?php

declare(strict_types=1);

namespace App\Tests\Behat\Context;

use App\Tests\Behat\Page\Book\CreatePageInterface;
use Behat\Behat\Context\Context;
use Sylius\Behat\NotificationType;
use Sylius\Behat\Service\NotificationCheckerInterface;

final class BookContext implements Context
{
    private CreatePageInterface $createPage;
    private NotificationCheckerInterface $notificationChecker;

    public function __construct(CreatePageInterface $createPage, NotificationCheckerInterface $notificationChecker)
    {
        $this->createPage = $createPage;
        $this->notificationChecker = $notificationChecker;
    }

    /**
     * @When I open the create new book page
     */
    public function iOpenTheCreateNewBookPage()
    {
        $this->createPage->open();
    }

    /**
     * @When I fill the book title with :arg1
     */
    public function iFillTheBookTitleWith($title)
    {
        $this->createPage->fillWithTitle($title);
    }

    /**
     * @When I add it
     */
    public function iAddIt()
    {
        $this->createPage->add();
    }

    /**
     * @Then I should be notified that new book was created
     */
    public function iShouldBeNotifiedThatNewBookWasCreated()
    {
        $this->notificationChecker->checkNotification('Book has been successfully created.', NotificationType::success());
    }
}
