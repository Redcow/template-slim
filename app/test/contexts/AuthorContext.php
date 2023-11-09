<?php

namespace contexts;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class AuthorContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given there is an author called :arg1 which has written a book called :arg2
     */
    public function thereIsAnAuthorCalledWhichHasWrittenABookCalled($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @When I set :arg1 as his first name
     */
    public function iSetAsHisFirstName($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When I set :arg1 as his last name
     */
    public function iSetAsHisLastName($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I should have a new author in my memory system called :arg1
     */
    public function iShouldHaveANewAuthorInMyMemorySystemCalled($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I should not have a new author in my memory system called :arg1
     */
    public function iShouldNotHaveANewAuthorInMyMemorySystemCalled($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given there is an anonymous author which has written a book called :arg1
     */
    public function thereIsAnAnonymousAuthorWhichHasWrittenABookCalled($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When I set his first name empty
     */
    public function iSetHisFirstNameEmpty()
    {
        throw new PendingException();
    }

    /**
     * @When I set his last name empty
     */
    public function iSetHisLastNameEmpty()
    {
        throw new PendingException();
    }
}
