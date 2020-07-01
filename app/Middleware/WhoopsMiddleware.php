<?php

namespace App\Middlewares;

use DI\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Whoops\Handler\JsonResponseHandler;
use Whoops\Handler\PrettyPageHandler;
use Whoops\RunInterface;

class WhoopsMiddleware
{
    protected Container $container;
    protected RunInterface $whoops;
    protected PrettyPageHandler $pageHandler;
    protected JsonResponseHandler $jsonHandler;

    /**
     * Create a new WhoopseMiddleware object.
     */
    public function __construct(
        Container $container,
        RunInterface $whoops,
        PrettyPageHandler $pageHandler,
        JsonResponseHandler $jsonHandler
    ) {
        $this->container = $container;
        $this->whoops = $whoops;
        $this->pageHandler = $pageHandler;
        $this->jsonHandler = $jsonHandler;
    }

    /**
     * Invoke the WhoopseMiddleware class.
     */
    public function __invoke(Request $request, RequestHandler $handler): ResponseInterface
    {
        $this->pageHandler->setPageTitle(
            sprintf('%s • UberGallery', $this->pageHandler->getPageTitle())
        );

        $this->whoops->pushHandler($this->pageHandler);

        if (in_array('application/json', explode(',', $request->getHeaderLine('Accept')))) {
            $this->whoops->pushHandler($this->jsonHandler);
        }

        $this->whoops->register();

        return $handler->handle($request);
    }
}
