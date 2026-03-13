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
#[McpResource(uri: 'swag://admin-users', name: 'swag-admin-users', description: 'Live list of Shopware admin users with their roles and active status.')]
class AdminUsersResource
{
    public function __construct(
        private readonly EntityRepository $userRepository,
    ) {
    }

    /**
     * @return array{uri: string, mimeType: string, text: string}
     */
    public function __invoke(): array
    {
        $criteria = new Criteria();
        $criteria->addAssociation('aclRoles');

        $users = $this->userRepository->search($criteria, Context::createDefaultContext());

        $result = [];
        foreach ($users->getElements() as $user) {
            $roles = [];
            foreach ($user->getAclRoles()?->getElements() ?? [] as $role) {
                $roles[] = $role->getName();
            }

            $result[] = [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'firstName' => $user->getFirstName(),
                'lastName' => $user->getLastName(),
                'active' => $user->getActive(),
                'admin' => $user->isAdmin(),
                'roles' => $roles,
            ];
        }

        return [
            'uri' => 'swag://admin-users',
            'mimeType' => 'application/json',
            'text' => json_encode($result, \JSON_THROW_ON_ERROR),
        ];
    }
}
