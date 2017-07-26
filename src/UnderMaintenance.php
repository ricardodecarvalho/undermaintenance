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
     * UnderMaintenance constructor.
     * @param bool $maintenance
     * @param array $ipsReleased
     */
    public function __construct($maintenance = false, $ipsReleased = [])
    {
        $this->maintenance = $maintenance;
        $this->ipsReleased = $ipsReleased;
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
            die('No momento estamos realizando uma manutenção em nosso site.');
        }
        return $next($request, $response);
    }
}