<?php declare(strict_types=1);

namespace Swag\McpAdminUsers\Mcp;

use Mcp\Capability\Attribute\McpPrompt;
use Shopware\Core\Framework\Log\Package;

/**
 * @experimental stableVersion:v6.8.0 feature:MCP_SERVER
 */
#[Package('framework')]
#[McpPrompt(name: 'swag-admin-context', description: 'Context about Shopware admin user and role management for AI tool interaction.')]
class AdminContextPrompt
{
    /**
     * @return list<array{role: string, content: string}>
     */
    public function __invoke(): array
    {
        return [
            [
                'role' => 'user',
                'content' => <<<'PROMPT'
You have access to Shopware admin user and ACL role management tools.

## Available tools
- `swag-admin-users-admin-users` — lists all admin users with their usernames, emails, active status, and assigned roles
- `swag-admin-users-acl-roles` — lists all ACL roles with their names, descriptions, and privileges

## Key concepts
- Admin users can be assigned one or more ACL roles that define their permissions.
- An admin user with `admin: true` has full access regardless of ACL roles.
- Privileges follow the pattern `entity:operation` (e.g. `product:read`, `order:update`).

## Common workflows

### Audit user access
1. Call `swag-admin-users-admin-users` to see all users and their assigned roles.
2. Call `swag-admin-users-acl-roles` to inspect which privileges each role grants.

### Find overprivileged users
1. Call `swag-admin-users-acl-roles` to list all roles.
2. Cross-reference with `swag-admin-users-admin-users` to see which users hold sensitive roles.
PROMPT,
            ],
        ];
    }
}
