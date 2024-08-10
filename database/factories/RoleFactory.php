<?php


namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Permission\Models\Role;

class RoleFactory extends Factory
{
 
    public function definition(): array
    {
        // $name = $this->faker->name();
        $name = $this->faker->sentence;
        return [
            'name' => $name,
        ];
    }
}
