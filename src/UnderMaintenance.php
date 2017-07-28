<?php

namespace UnderMaintenance;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class UnderMaintenance
 * @package UnderMaintenance
 */
class UnderMaintenance
{
    /**
     * @var bool
     */
    private $maintenance;

    /**
     * @var array
     */
    private $ipsReleased;

    /**
     * @var array
     */
    private $message;

    /**
     * @var null
     */
    private $pathCache;

    /**
     * UnderMaintenance constructor.
     * @param bool $maintenance
     * @param array $ipsReleased
     * @param array $message
     * @param null $pathCache
     */
    public function __construct($maintenance = false, $ipsReleased = [], $message = [], $pathCache = null)
    {
        $this->maintenance = $maintenance;
        $this->ipsReleased = $ipsReleased;
        $this->message = $message;
        $this->pathCache = $pathCache;
    }

    /**
     * Execute the middleware
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param callable $next
     * @return mixed
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next)
    {
        $ip = filter_input(INPUT_SERVER, 'REMOTE_ADDR');
        if ($this->maintenance && !in_array($ip, $this->ipsReleased)) {
            $this->loadPage();
        }
        return $next($request, $response);
    }

    private function loadPage()
    {
        $loader = new \Twig_Loader_Filesystem(__DIR__ . '/view');
        $twig = new \Twig_Environment($loader, array(
            'cache' => $this->pathCache // or false
        ));
        $template = $twig->load('index.twig');
        echo $template->render(array('msg' => $this->message));
        die;
    }

}