<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Project;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;

    /**
     * Test creation of a project
     *
     * @return void
     */
    public function test_a_user_can_create_a_project()
    {
        $this->withoutExceptionHandling();

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];

        $this->post('/projects', $attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['title']);
    }

    public function test_a_user_can_view_a_project()
    {
        $this->withoutExceptionHandling();

        $project = Project::factory()->create();

        $this->get('/projects/' . $project->id)
            ->assertSee($project->title)
            ->assertSee($project->description);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function test_a_project_requires_a_title()
    {
        $attributes = Project::factory()->raw(['title' => '']);
        $this->post('/projects', $attributes)->assertSessionHasErrors('title');
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function test_a_project_requires_a_description()
    {
        $attributes = Project::factory()->raw(['description' => '']);

        $this->post('/projects', $attributes)->assertSessionHasErrors('description');
    }
}
