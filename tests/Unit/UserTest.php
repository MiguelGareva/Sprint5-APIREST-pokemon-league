<?php 

namespace Tests\Unit;

use App\Models\User;
use App\Models\Trainer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;

class UserTest extends TestCase{

    use RefreshDatabase;

    /** @test */
    public function testUserCanHaveATrainer(){

        $user = User::factory()->create();
        $trainer = Trainer::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Trainer::class, $user->trainer);
        $this->assertEquals($trainer->id, $user->trainer->id);
    }
}