<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->title,
            'name_file' => $this->faker->title,
            'path' => 'public/project/'.$this->faker->title,
            'desc' => $this->faker->text,
            'user_id' => 1,
            'category_id' => 1,
        ];
    }
}
