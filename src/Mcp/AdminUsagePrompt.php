<?php declare(strict_types=1);

namespace Swag\McpAdminUsers\Mcp;

use Mcp\Capability\Attribute\McpPrompt;
use Shopware\Core\Framework\Log\Package;

/**
 * @experimental stableVersion:v6.8.0 feature:MCP_SERVER
 */
#[Package('framework')]
#[McpPrompt(name: 'swag-admin-usage', description: 'Safety guidelines for using the Shopware admin user MCP tools.')]
class AdminUsagePrompt
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
When using admin user and ACL role tools, follow these guidelines:

## Read-only tools
`swag-admin-users-admin-users` and `swag-admin-users-acl-roles` are read-only — they never modify data.
Use them freely to audit the current state.

## Privacy
- Admin user data (emails, names) is sensitive. Do not expose it unless explicitly asked.
- Treat privilege lists as internal configuration — do not share with untrusted parties.

## Interpreting results
- A user with `admin: true` bypasses all ACL checks — treat them as superusers.
- An empty `privileges` list on a role does not mean no access; inherited privileges may apply.
- Inactive users (`active: false`) cannot log in but their data is retained.
PROMPT,
            ],
        ];
    }
}
