# SwagMcpAdminUsers

Shopware plugin demonstrating how to register a custom MCP tool.

Part of the [MCP Server POC](https://github.com/shopware/shopware/pull/15346) for Shopware.

## What it does

Registers an MCP tool (`swag-admin-users-admin-users`) that lists all admin users with their roles, emails, and active status.

## Setup

```bash
bin/console plugin:refresh
bin/console plugin:install --activate SwagMcpAdminUsers
```

## Files

- `src/Mcp/AdminUsersTool.php` -- Tool class with `#[McpTool]` attribute
- `src/Resources/config/services.xml` -- Service definition with `shopware.mcp.tool` tag
