<?php

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Admin
            $table->string('slug'); // admin
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            //Long way: $table->foreign('role_id')->references('id')->on('roles');
            //constrained -> valid foreign key
            $table->foreignId('role_id')->constrained();
        });

        $roles = [
            'user' => 'User',
            'admin' => 'Admin',
        ];

        foreach ($roles as $slug => $name) {
            DB::table('roles')->insert([
                'slug' => $slug,
                'name' => $name,
            ]);
        }

        $user = new User();

        $user->email = "test@usc.edu";
        $user->name = 'test';
        $user->password = Hash::make('123'); //encrypt using bcrypt
        $userRole = Role::where('slug', '=', 'user')->first();

        $user->role()->associate($userRole);

        $user->save();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropColumns('users', ['role_id']);
        Schema::dropIfExists('roles');
    }
};
