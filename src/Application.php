<?php

declare(strict_types=1);

namespace app;

use Slim\App;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use app\router\ApplicationRouter;

class Application
{
    public static self $instance;

    public App $app;
    public Twig $twig;

    public ApplicationRouter $router;

    public function __construct(App $app)
    {
        self::$instance = $this;

        $this->twig = Twig::create(__DIR__ . "/../templates", [
            "cache" => false,
        ]);

        $this->app = $app;

        $this->router = new ApplicationRouter();

        $this->middlewares();
    }

    public function middlewares(): void
    {
        $this->app->addRoutingMiddleware();
        $this->app->addErrorMiddleware(true, true, true);

        $this->app->add(TwigMiddleware::create($this->app, $this->twig));
    }

    public function execute(): void
    {
        $this->app->run();
    }

    public function getApp(): App
    {
        return $this->app;
    }

    public function getTwig(): Twig
    {
        return $this->twig;
    }

    public static function getInstance(): self
    {
        return self::$instance;
    }
}
