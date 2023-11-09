<?php

namespace contexts;

use Behat\Behat\Context\Context;
use Behat\Testwork\Hook\Scope\AfterSuiteScope;
use Behat\Testwork\Hook\Scope\BeforeSuiteScope;
use Infrastructure\Mail\SymfonyMailer;
use Model\User\SignUp\SignUp;
use Model\User\SignUp\SignUpInput;
use Model\User\SignUp\SignUpOutput;
use PHPUnit\Framework\Assert;
use Service\MailerServiceService;
use Service\UserRepository;
use Test\sql\TestDatabase;
use function Config\loadEnvConfig;

/**
 * Defines application features from the specific context.
 */
class SecurityContext implements Context
{
    private SignUp $useCase;
    private SignUpInput $input;
    private SignUpOutput $output;

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
        $this->useCase = new SignUp(
            new UserRepository(TestDatabase::getDb()),
            new MailerServiceService(new SymfonyMailer())
        );
        $this->input = new SignUpInput(
            "init@cogelec",
            "test#test",
            fn () => "url"
        );
        $this->output = new SignUpOutput();
    }

    /**
     * @Given I have the following email :email
     */
    public function iHaveTheFollowingEmail($email)
    {
        $this->input = new SignUpInput(
            $email,
            "test#test",
            fn () => "url"
        );
    }

    /**
     * @When I try to create an account
     */
    public function iTryToCreateAnAccount()
    {
        $this->useCase->execute(
            $this->input,
            $this->output
        );
    }

    /**
     * @Then I get the message :message
     */
    public function iGetTheMessage($message)
    {
        Assert::assertEquals($message, $this->output->getMessage());
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
