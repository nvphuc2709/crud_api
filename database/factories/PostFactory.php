<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->text(10),
            'description' => $this->faker->text(15),
            'content' => $this->faker->text(20),
            'user_id' => User::all()->random()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}