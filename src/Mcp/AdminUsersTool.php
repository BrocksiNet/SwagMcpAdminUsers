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
#[McpTool(name: 'swag-admin-users-admin-users', description: 'List all admin users of the Shopware instance. Returns usernames, emails, and active status.')]
class AdminUsersTool extends McpToolResponse
{

    public function __construct(
        private readonly EntityRepository $userRepository,
        private readonly McpContextProvider $contextProvider,
    ) {
    }

    public function __invoke(): string
    {
        try {
            $context = $this->contextProvider->getContext();

            $criteria = new Criteria();
            $criteria->addAssociation('aclRoles');

            $users = $this->userRepository->search($criteria, $context);

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
                    'timeZone' => $user->getTimeZone(),
                ];
            }

            return $this->success($result, ['total' => $users->getTotal()]);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage());
        }
    }
}
