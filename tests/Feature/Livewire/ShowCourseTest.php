<?php

use App\Livewire\ShowCourse;
use App\Models\Course;
use App\Models\Episode;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Livewire\Livewire;

it('renders successfully', function () {
    $course = Course::factory()
        ->has(Episode::factory()->state(['length_in_minutes' => 10])->count(10), 'episodes')
        ->create();
    
    $user = User::factory()->create();
    $user->courses()->attach($course);
    Livewire::actingAs($user)->test(ShowCourse::class, ['course' => $course])
        ->assertStatus(200);
});

it('shows course details', function () {
    // Arrange
    $course = Course::factory()
        ->has(Episode::factory()->state(['length_in_minutes' => 10])->count(10), 'episodes')
        ->create();

    $user = User::factory()->create();
    $user->courses()->attach($course);
    // Act & Assert
    Livewire::actingAs($user)->test(ShowCourse::class, ['course' => $course])
        ->assertOk()
        ->assertSeeText($course->title)
        ->assertSeeText($course->tagline)
        ->assertSeeText($course->description)
        ->assertSeeText($course->created_at->diffForHumans())
        ->assertSeeText($course->episodes_count . ' episodes')
        ->assertSeeText($course->formatted_length);
});

it('shows the episode list', function () {
    // Arrange
    $course = Course::factory()
        ->has(
            Episode::factory()
                ->count(3)
                ->state(new Sequence(
                    ['title' => 'First Episode', 'length_in_minutes' => 5],
                    ['title' => 'Second Episode', 'length_in_minutes' => 10],
                    ['title' => 'Third Episode', 'length_in_minutes' => 1],
                ))
        )
        ->create();

    $user = User::factory()->create();
    $user->courses()->attach($course);
    Livewire::actingAs($user)->test(ShowCourse::class, ['course' => $course])
        ->assertOk()
        ->assertSeeText('First Episode')
        ->assertSeeText('5 mins')
        ->assertSeeText('Second Episode')
        ->assertSeeText('10 mins')
        ->assertSeeText('Third Episode')
        ->assertSeeText('1 min');
});

it('shows the start watching action', function () {
    $course = Course::factory()
        ->has(Episode::factory())
        ->create();
    
    Livewire::test(ShowCourse::class, ['course' => $course])
        ->assertSee('Start Watching');
});

it('shows the continue watching action', function () {
    $course = Course::factory()
        ->has(Episode::factory())
        ->create();
    $user = User::factory()->create();
    $user->courses()->attach($course);
    
    Livewire::actingAs($user)->test(ShowCourse::class, ['course' => $course])
        ->assertSee('Continue Watching');
});

it('shows the course tags', function() {
    $course = Course::factory()
        ->has(Episode::factory())
        ->has(
            Tag::factory()
                ->count(2)
                ->state(new Sequence(
                    ['name' => 'Laravel'],
                    ['name' => 'Filament'],
                ))
        )
        ->create();
    $user = User::factory()->create();
    $user->courses()->attach($course);
    
    Livewire::actingAs($user)->test(ShowCourse::class, ['course' => $course])
        ->assertOk()
        ->assertSeeText([
            'Laravel',
            'Filament'
        ]);
});