<?php declare(strict_types=1);

namespace Swag\McpAdminUsers\Mcp;

use Mcp\Capability\Attribute\McpResource;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\Log\Package;

/**
 * @experimental stableVersion:v6.8.0 feature:MCP_SERVER
 */
#[Package('framework')]
#[McpResource(uri: 'swag://admin-roles', name: 'swag-admin-roles', description: 'Live list of Shopware ACL roles with their privileges.')]
class AdminRolesResource
{
    public function __construct(
        private readonly EntityRepository $aclRoleRepository,
    ) {
    }

    /**
     * @return array{uri: string, mimeType: string, text: string}
     */
    public function __invoke(): array
    {
        $roles = $this->aclRoleRepository->search(new Criteria(), Context::createDefaultContext());

        $result = [];
        foreach ($roles->getElements() as $role) {
            $result[] = [
                'id' => $role->getId(),
                'name' => $role->getName(),
                'description' => $role->getDescription(),
                'privileges' => $role->getPrivileges(),
            ];
        }

        return [
            'uri' => 'swag://admin-roles',
            'mimeType' => 'application/json',
            'text' => json_encode($result, \JSON_THROW_ON_ERROR),
        ];
    }
}
