<?php declare(strict_types=1);

namespace Swag\McpAdminUsers\Mcp;

use Mcp\Capability\Attribute\McpTool;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Mcp\Context\McpContextProvider;
use Shopware\Core\Framework\Mcp\Tool\McpToolResponse;

/**
 * @experimental stableVersion:v6.8.0 feature:MCP_SERVER
 */
#[Package('framework')]
#[McpTool(name: 'swag-admin-users-acl-roles', description: 'List all ACL roles with their assigned privileges.')]
class AdminRolesTool extends McpToolResponse
{

    public function __construct(
        private readonly EntityRepository $aclRoleRepository,
        private readonly McpContextProvider $contextProvider,
    ) {
    }

    public function __invoke(): string
    {
        try {
            $context = $this->contextProvider->getContext();

            $roles = $this->aclRoleRepository->search(new Criteria(), $context);

            $result = [];
            foreach ($roles->getElements() as $role) {
                $result[] = [
                    'id' => $role->getId(),
                    'name' => $role->getName(),
                    'description' => $role->getDescription(),
                    'privileges' => $role->getPrivileges(),
                ];
            }

            return $this->success($result, ['total' => $roles->getTotal()]);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage());
        }
    }
}
