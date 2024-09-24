<?php

declare(strict_types=1);

namespace app\controller;

use app\Application;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class HomeController
{
    public function home_get(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $view = Twig::fromRequest($request);
        return $view->render($response, "home/home.html.twig");
    }
}
