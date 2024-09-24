<?php

declare(strict_types=1);

namespace app\controller;

use app\model\ProfileModel;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class AuthController
{
    public function register_get(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $view = Twig::fromRequest($request);
        return $view->render($response, "auth/register.html.twig");
    }

    public function login_get(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $view = Twig::fromRequest($request);
        return $view->render($response, "auth/login.html.twig");
    }

    public function no_auth(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $view = Twig::fromRequest($request);
        return $view->render($response, "auth/noauth.html.twig");
    }

    public function register_post(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $data = $request->getParsedBody();

        if (
            empty($data["nombre"]) ||
            empty($data["password"] || empty($data["email"]))
        ) {
            return $this->no_auth($request, $response, $args);
        }

        $user = ProfileModel::create([
            "nombre" => $data["nombre"],
            "password" => $data["password"],
            "email" => $data["email"],
        ]);

        return $response
            ->withHeader("Location", "/auth/login")
            ->withStatus(302);
    }

    public function login_post(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $data = $request->getParsedBody();

        if (empty($data["email"]) || empty($data["password"])) {
            return $this->no_auth($request, $response, $args);
        }

        $user = ProfileModel::where("email", $data["email"])->first();

        if ($user === null) {
            return $this->no_auth($request, $response, $args);
        }

        if (!password_verify($data["password"], $user->password)) {
            return $this->no_auth($request, $response, $args);
        }

        $_SESSION["user_id"] = $user->id;

        return $response->withHeader("Location", "/")->withStatus(302);
    }

    public function logout_get(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $_SESSION = [];
        return $response
            ->withHeader("Location", "/auth/login")
            ->withStatus(302);
    }
}
