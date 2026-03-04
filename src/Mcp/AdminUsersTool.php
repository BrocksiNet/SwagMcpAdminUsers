<?php declare(strict_types=1);

namespace Swag\McpAdminUsers\Mcp;

use Mcp\Capability\Attribute\McpTool;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;

#[McpTool(name: 'swag-admin-users-admin-users', description: 'List all admin users of the Shopware instance. Returns usernames, emails, and active status.')]
class AdminUsersTool
{
    public function __construct(
        private readonly EntityRepository $userRepository,
    ) {
    }

    public function __invoke(): string
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
                'timeZone' => $user->getTimeZone(),
            ];
        }

        return json_encode([
            'total' => $users->getTotal(),
            'users' => $result,
        ], \JSON_THROW_ON_ERROR | \JSON_PRETTY_PRINT);
    }
}
