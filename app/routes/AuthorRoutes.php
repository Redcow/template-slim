<?php

namespace Route;

use Controller\Author\GetAuthorListController;
use Controller\AuthorController;
use Middleware\RouteValidator;
use Slim\App;
use Slim\Routing\RouteCollectorProxy as Group;

return function (App $app) {
    $app->group('/authors', function(Group $group) {

        //$group->get('', [AuthorController::class, 'getAuthors'])->setName('get_authors');

        $group->get('', GetAuthorListController::class )->setName('get_authors');//->addMiddleware(new RouteValidator());

        $group->post('', [AuthorController::class, 'createAuthor'])->setName('create_author');

        $group->get('/{id:[0-9]+}', [AuthorController::class, 'getAuthor'])->setName('get_author');

        $group->delete('/{id:[0-9]+}', [AuthorController::class, 'deleteAuthor'])->setName('delete_author');
    });
};