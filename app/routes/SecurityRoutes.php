<?php

namespace Route;

use Controller\Security\ActivateAccountController;
use Controller\Security\SignInController;
use Controller\Security\SignUpController;
use Slim\App;
use Slim\Routing\RouteCollectorProxy as Group;

return function (App $app) {


    $app->group('/auth', function(Group $group) {

        $group->post('/signin', SignInController::class )->setName('sign_in');

        $group->post('/signup', SignUpController::class)->setName('sign_up');

        $group->get('/verify/{token}', ActivateAccountController::class)->setName('activate_account');
    });
};