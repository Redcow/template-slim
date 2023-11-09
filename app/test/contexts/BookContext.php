<?php

namespace contexts;

use Behat\Behat\Context\Context;
use Behat\Testwork\Hook\Scope\AfterSuiteScope;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use Model\Book\Delete\DeleteBook;
use Model\Book\Delete\DeleteBookInput as Input;
use Model\Book\Delete\DeleteBookOutput as Output;
use PHPUnit\Framework\Assert;
use Service\AuthorRepository;
use Service\BookRepository;
use Test\mocks\AuthSecurityMock;
use Test\sql\TestDatabase;
use function Config\loadEnvConfig;

/**
 * Defines application features from the specific context.
 */
class BookContext implements Context
{
    private Input $input;
    private Output $output;
    private DeleteBook $useCase;
    private AuthorRepository $authorRepository;

    /**
     * @BeforeSuite
     * @database
     */
    public static function prepare(BeforeSuiteScope $_scope): void
    {
        loadEnvConfig();
        $database = TestDatabase::getDb();
        $database->resetDatabase();
    }

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        loadEnvConfig();
        $authorRepository = new AuthorRepository(TestDatabase::getDb());
        $bookRepository = new BookRepository(TestDatabase::getDb());
        
        $this->authorRepository = $authorRepository;
        
        $this->input = new Input();
        $this->output = new Output();
        $this->useCase = new DeleteBook(
            $bookRepository, $authorRepository
        );
    }

    /**
     * @Given I am logged as with user id :userId
     */
    public function iAmLoggedAsWithUserId($userId)
    {
        AuthSecurityMock::mockUserId((int)$userId);
    }

    /**
     * @When I try to delete the book with id :bookId
     */
    public function iTryToDeleteTheBookWithId($bookId)
    {
        /*try {
            $this->bookRepository->deleteById((int)$bookId);
            $this->output->setMessage('Your book has been deleted');
        } catch (\Exception $exception) {
            $this->output->setMessage("It's not your book!");
        }*/


        // second temps

        // logique écrite et testée mais elle n'est pas figée car elle contient une suite de traitements à déclencher dans le bon ordre
        // Pour consolider cette logique, on doit la réunir en un seul appel -> création du useCase


        $this->input->setBookId((int)$bookId);

        $this->useCase->execute(
            $this->input,
            $this->output
        );

        // on peut encore faire mieux, voyez-vous comment ?
    }

    /**
     * @Then I get the message :message
     */
    public function iGetTheMessage($message)
    {
        Assert::assertEquals(
            $message,
            $this->output->getMessage()
        );
    }

    /**
     * @Then The author with id :authorId does not exist anymore
     */
    public function theAuthorWithIdDoesNotExistAnymore($authorId)
    {
        Assert::assertNull(
            $this->authorRepository->getById($authorId)
        );
    }

    /**
     * @AfterSuite
     * @database
     */
    static public function resetDatabase(AfterSuiteScope $_scope): void
    {
        $database = TestDatabase::getDb();
        $database->resetData();
    }
}
