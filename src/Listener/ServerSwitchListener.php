<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  wenber.yu@creative-life.club
 * @license  https://github.com/wilbur-yu/hyperf-server-switch/blob/main/LICENSE
 */
namespace WilburYu\HyperfServerSwitch\Listener;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Psr\Container\ContainerInterface;
use RuntimeException;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\InputOption;

/**
 * @Listener
 */
class ServerSwitchListener implements ListenerInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function listen(): array
    {
        return [
            ConsoleCommandEvent::class,
        ];
    }

    /**
     * @param ConsoleCommandEvent $event
     */
    public function process(object $event)
    {
        if ($event instanceof ConsoleCommandEvent) {
            $command = $event->getCommand();
            $command->addOption('server', 'S', InputOption::VALUE_OPTIONAL, '需要启动的服务');
            $input = $event->getInput();
            $input->bind($command->getDefinition());

            if ($input->getOption('server') != null) {
                $config = $this->container->get(ConfigInterface::class);
                $servers = $config->get('server.servers', []);
                $result = [];
                foreach ($servers as $server) {
                    if ($input->getOption('server') == $server['name']) {
                        $result[] = $server;
                    }
                }

                if (empty($result)) {
                    throw new RuntimeException(500, '服务名不存在');
                }

                $config->set('server.servers', $result);
            }
        }
    }
}
