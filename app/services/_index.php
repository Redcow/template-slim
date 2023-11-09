<?php

namespace Service;

use DI\Container;
use Infrastructure\Database\MysqlDatabase;
use Infrastructure\Mail\SymfonyMailer;
use Model\Author\AuthorRepositoryInterface;
use Model\Author\GetList\GetAuthorList;
use Model\Book\BookRepositoryInterface;
use Model\Book\Delete\DeleteBook;
use Model\MailerServiceInterface;
use Model\Security\JwtServiceInterface;
use Model\Security\Signin\Signin;
use Model\User\SignUp\SignUp;
use Model\User\UserRepositoryInterface;
use Slim\Factory\AppFactory;

function loadServices(): void
{
    $serviceContainer = new Container();

    loadRepositories($serviceContainer);

    $serviceContainer->set(MailerServiceInterface::class, fn () => new MailerService(new SymfonyMailer()));

    $serviceContainer->set(JwtServiceInterface::class, fn () => new JwtService());

    loadFeatures($serviceContainer);

    AppFactory::setContainer($serviceContainer);
}

function loadRepositories(Container $serviceContainer): void
{
    $serviceContainer->set(AuthorRepositoryInterface::class, fn () => new AuthorRepository(MysqlDatabase::getDb()));
    $serviceContainer->set(BookRepositoryInterface::class, fn () => new BookRepository(MysqlDatabase::getDb()));
    $serviceContainer->set(UserRepositoryInterface::class, fn () => new UserRepository(MysqlDatabase::getDb()));
}

function loadFeatures(Container $serviceContainer): void
{
    // Signup as user
    $serviceContainer->set(SignUp::class, fn () => new SignUp(
        $serviceContainer->get(UserRepositoryInterface::class),
        $serviceContainer->get(MailerServiceInterface::class),
    ));

    // Signin as user
    $serviceContainer->set(Signin::class, fn () => new Signin(
        $serviceContainer->get(UserRepositoryInterface::class),
        $serviceContainer->get(JwtServiceInterface::class)
    ));

    // Get author list
    $serviceContainer->set(GetAuthorList::class, fn () => new GetAuthorList(
        $serviceContainer->get(AuthorRepositoryInterface::class)
    ));

    // Delete book
    $serviceContainer->set(DeleteBook::class, fn () => new DeleteBook(
        $serviceContainer->get(BookRepositoryInterface::class),
        $serviceContainer->get(AuthorRepositoryInterface::class)
    ));
}