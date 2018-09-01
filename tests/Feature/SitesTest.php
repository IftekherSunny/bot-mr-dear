<?php

namespace Tests\Feature;

use App\OhDear\Services\DearApi;
use Tests\Fakes\DearApiEmpty;
use Tests\TestCase;

class SitesTest extends TestCase
{
    /** @test */
    public function can_show_list_of_sites()
    {
        $this->bot->receives('/sites')
            ->assertReply('✅ example.com - site is up! 💪')
            ->assertReply('🔴 failed.example.com - site is down! 😱');
    }

    /** @test */
    public function can_say_there_are_no_sites()
    {
        $this->app->bind(DearApi::class, DearApiEmpty::class);

        $this->bot->receives('/sites')
            ->assertReply('There are no sites on your account.')
            ->assertReply('Perhaps you want to add a new one right now? use the command /newsite');
    }
}
