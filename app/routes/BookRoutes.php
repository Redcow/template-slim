<?php

namespace Route;

use Controller\Book\DeleteBookController;
use Slim\App;
use Slim\Routing\RouteCollectorProxy as Group;

return function (App $app) {

    $app->group('/books', function(Group $group) {

        $group->delete('/{id:[0-9]+}', DeleteBookController::class )->setName('delete_book');

    });
};