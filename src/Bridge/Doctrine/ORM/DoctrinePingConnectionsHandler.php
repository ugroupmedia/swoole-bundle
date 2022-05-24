<?php

declare(strict_types=1);

namespace K911\Swoole\Bridge\Doctrine\ORM;

use Doctrine\DBAL\Connection;
use Doctrine\Persistence\ManagerRegistry;
use K911\Swoole\Server\RequestHandler\RequestHandlerInterface;
use Swoole\Http\Request;
use Swoole\Http\Response;

final class DoctrinePingConnectionsHandler implements RequestHandlerInterface
{

    public function __construct(
        private RequestHandlerInterface $decorated,
        private ManagerRegistry $registry)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request, Response $response): void
    {
        foreach ($this->registry->getConnections() as $connection) {
            if (!$connection instanceof Connection) {
                continue;
            }

            $this->pingConnection($connection);
        }

        $this->decorated->handle($request, $response);
    }

    private function pingConnection(Connection $connection): void
    {
        if (!$connection->isConnected()) {
            return;
        }
        if ($connection->ping()) {
            return;
        }
        $connection->close();
        $connection->connect();
    }
}
