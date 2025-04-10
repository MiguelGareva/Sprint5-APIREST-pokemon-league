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

    
    public function testUserCanHaveATrainer(){

        $user = User::factory()->create();
        $trainer = Trainer::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Trainer::class, $user->trainer);
        $this->assertEquals($trainer->id, $user->trainer->id);
    }

    
    public function testUserCanHaveRoles(){

        Role::create(['name' => 'admin']);
        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->assertTrue($user->hasRole('admin'));

    }

    public function testAdminUserCanExistWithoutTrainer(){

        Role::create(['name' =>'admin']);
        Role::create(['name' => 'trainer']);

        $adminUser = User::factory()->create();
        $adminUser->assignRole('admin');

        $trainerUser = User::factory()->create();
        $trainerUser->assignRole('trainer');
        Trainer::factory()->create(['user_id' => $trainerUser->id]);

        $this->assertNull($adminUser->trainer);
        $this->assertInstanceOf(Trainer::class, $trainerUser->trainer);
    }
}