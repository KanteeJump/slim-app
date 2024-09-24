<?php

declare(strict_types=1);

namespace app\router;

use app\Application;
use app\controller\AuthController;
use app\controller\HomeController;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Routing\RouteCollectorProxy;
use Slim\Views\Twig;

class ApplicationRouter
{
    public function __construct()
    {
        $app = Application::getInstance()->getApp();

        $noAuthMiddleware = function (
            Request $request,
            RequestHandler $handler
        ) use ($app) {
            if (!isset($_SESSION["user_id"])) {
                $response = $app->getResponseFactory()->createResponse();
                $twig = Twig::fromRequest($request);
                return $twig->render($response, "auth/noauth.html.twig");
            }
            return $handler->handle($request);
        };

        $userMiddleware = function (
            Request $request,
            RequestHandler $handler
        ) use ($app) {
            if (isset($_SESSION["user_id"])) {
                $response = $app->getResponseFactory()->createResponse();
                $twig = Twig::fromRequest($request);
                return $twig->render($response, "auth/user_auth.html.twig", [
                    "name" => $_SESSION["user_id"],
                ]);
            }
            return $handler->handle($request);
        };

        $app->get("/", [HomeController::class, "home_get"])->add(
            $noAuthMiddleware
        );

        //Vistas de autenticación
        $app->group("/auth", function (RouteCollectorProxy $group) {
            $group->get("/login", [AuthController::class, "login_get"]);
            $group->get("/register", [AuthController::class, "register_get"]);

            $group->post("/login", [AuthController::class, "login_post"]);
            $group->post("/register", [AuthController::class, "register_post"]);
        })->add($userMiddleware);

        $app->get("/logout", [AuthController::class, "logout_get"]);
    }
}
