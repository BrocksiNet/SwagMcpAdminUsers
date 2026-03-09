<?php declare(strict_types=1);

namespace Swag\McpAdminUsers;

use Shopware\Core\Framework\Feature;
use Shopware\Core\Framework\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SwagMcpAdminUsers extends Plugin
{
    public function build(ContainerBuilder $container): void
    {
        if (!Feature::has('MCP_SERVER')) {
            return;
        }

        parent::build($container);
    }
}
