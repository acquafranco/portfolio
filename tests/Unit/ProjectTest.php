<?php

namespace Tests\Unit;

use App\Portfolio\Project;
use App\Portfolio\ProjectStatus;
use PHPUnit\Framework\TestCase;

class ProjectTest extends TestCase
{
    private function project(?string $url): Project
    {
        return new Project(
            slug: 'demo', name: 'demo', order: 1, featured: true, status: ProjectStatus::Production,
            year: 2026, url: $url, repository: null, links: [], accent: '#000000', logo: null, cover: null,
            coverSmall: null, stack: ['PHP'], category: 'c', tagline: 't', summary: 's', problem: 'p',
            solution: 'so', features: ['f'], audience: null, role: null,
        );
    }

    public function test_domain_is_the_bare_host(): void
    {
        $this->assertSame('ascento.online', $this->project('https://www.ascento.online/es?x=1')->domain());
        $this->assertSame('domus.on-forge.com', $this->project('https://domus.on-forge.com')->domain());
        $this->assertNull($this->project(null)->domain());
    }

    public function test_initials(): void
    {
        $this->assertSame('D', $this->project(null)->initials());
    }

    public function test_status_values(): void
    {
        $this->assertSame(['production', 'development', 'experimental'], ProjectStatus::values());
    }
}
