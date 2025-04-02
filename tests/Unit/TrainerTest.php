<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Trainer;
use App\Models\Pokemon;
use App\Models\Battle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainerTest extends TestCase{

    use RefreshDatabase;

     /** @test */
     public function testTrainerBelongsToUser()
     {
         $user = User::factory()->create();
         $trainer = Trainer::factory()->create(['user_id' => $user->id]);
         
         $this->assertInstanceOf(User::class, $trainer->user);
         $this->assertEquals($user->id, $trainer->user->id);
     }
}