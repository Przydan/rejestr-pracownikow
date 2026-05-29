<?php

declare(strict_types=1);

namespace App\Boost\Agents;

use Laravel\Boost\Contracts\SupportsGuidelines;
use Laravel\Boost\Contracts\SupportsMcp;
use Laravel\Boost\Contracts\SupportsSkills;
use Laravel\Boost\Install\Agents\Agent;
use Laravel\Boost\Install\Enums\McpInstallationStrategy;
use Laravel\Boost\Install\Enums\Platform;

class Antigravity extends Agent implements SupportsGuidelines, SupportsMcp, SupportsSkills
{
    public function name(): string
    {
        return 'antigravity';
    }

    public function displayName(): string
    {
        return 'Antigravity';
    }

    public function systemDetectionConfig(Platform $platform): array
    {
        return match ($platform) {
            Platform::Darwin, Platform::Linux => [
                'command' => 'command -v antigravity',
            ],
            Platform::Windows => [
                'command' => 'cmd /c where antigravity 2>nul',
            ],
        };
    }

    public function projectDetectionConfig(): array
    {
        return [
            'paths' => ['.antigravitycli'],
            'files' => ['AGENTS.md'],
        ];
    }

    public function mcpInstallationStrategy(): McpInstallationStrategy
    {
        return McpInstallationStrategy::FILE;
    }

    public function mcpConfigPath(): string
    {
        return config('boost.agents.antigravity.mcp_config_path', 'mcp.json');
    }

    public function guidelinesPath(): string
    {
        return config('boost.agents.antigravity.guidelines_path', 'AGENTS.md');
    }

    public function mcpConfigKey(): string
    {
        return 'mcpServers';
    }

    public function defaultMcpConfig(): array
    {
        return [
            'mcpServers' => [],
        ];
    }

    public function skillsPath(): string
    {
        return config('boost.agents.antigravity.skills_path', '.agents/skills');
    }
}
