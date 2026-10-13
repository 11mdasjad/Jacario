<?php

namespace Tests\Feature;

use Tests\TestCase;

class DeploymentCheckTest extends TestCase
{
    public function test_deployment_check_command_runs_successfully(): void
    {
        $this->artisan('deploy:check')
            ->assertSuccessful();
    }
}
