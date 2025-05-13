<?php

use App\Livewire\WatchEpisode;
use App\Models\Course;
use App\Models\Episode;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;

it('renders successfully', function () {
    $course = Course::factory()
        ->has(Episode::factory()->state(['vimeo_id' => '123456789']), 'episodes')
        ->create();

    $user = User::factory()->create();
    $user->courses()->attach($course);

    Livewire::actingAs($user)->test(WatchEpisode::class, ['course' => $course])
        ->assertStatus(200);
});

it('shows the first episode if none is provided', function () {
    $course = Course::factory()
        ->has(Episode::factory(2)->state(new Sequence(
            ['overview' => 'First episode overview'],
            ['overview' => 'Second episode overview'],
        )), 'episodes')
        ->create();

    $user = User::factory()->create();
    $user->courses()->attach($course);
    Livewire::actingAs($user)->test(WatchEpisode::class, ['course' => $course])
        ->assertOk()
        ->assertSeeText($course->episodes->first()->overview);
});

it('shows the provided episode', function () {
    $course = Course::factory()
        ->has(Episode::factory(2)->state(new Sequence(
            ['overview' => 'First episode overview', 'sort' => 1],
            ['overview' => 'Second episode overview', 'sort' => 2],
        )), 'episodes')
        ->create();

    $user = User::factory()->create();
    $user->courses()->attach($course);

    Livewire::actingAs($user)->test(WatchEpisode::class, ['course' => $course, 'episode' => $course->episodes->last()])
        ->assertOk()
        ->assertSeeText('Second episode overview');
});

it('shows the list of episodes', function() {
    // Arrange
    $course = Course::factory()
        ->has(
            Episode::factory()
                ->count(3)
                ->state(new Sequence(
                    ['title' => 'First Episode', 'sort' => 1],
                    ['title' => 'Second Episode', 'sort' => 2],
                    ['title' => 'Third Episode', 'sort' => 3],
                ))
        )
        ->create();

    $user = User::factory()->create();
    $user->courses()->attach($course);

    Livewire::actingAs($user)->test(WatchEpisode::class, ['course' => $course])
        ->assertOk()
        ->assertSeeInOrder([
            'First Episode',
            'Second Episode',
            'Third Episode'
        ]);
});

it('shows the video player', function () {
    $course = Course::factory()
        ->has(Episode::factory()->state(['vimeo_id' => '123456789']), 'episodes')
        ->create();

    $user = User::factory()->create();
    $user->courses()->attach($course);

    Livewire::actingAs($user)->test(WatchEpisode::class, ['course' => $course])
        ->assertOk()
        ->assertSee('<iframe src="https://player.vimeo.com/video/123456789"', false);
});

it('shows the list of episodes in ascending order', function() {
    // Arrange
    $course = Course::factory()
        ->has(
            Episode::factory()
                ->count(3)
                ->state(new Sequence(
                    ['title' => 'Second Episode', 'sort' => 2],
                    ['title' => 'Third Episode', 'sort' => 3],
                    ['title' => 'First Episode', 'sort' => 1],
                ))
        )
        ->create();

    $user = User::factory()->create();
    $user->courses()->attach($course);

    Livewire::actingAs($user)->test(WatchEpisode::class, ['course' => $course])
        ->assertOk()
        ->assertSeeInOrder([
            'First Episode',
            'Second Episode',
            'Third Episode'
        ]);
});

it('redirect to next episode after video ends', function () {
    $course = Course::factory()
        ->has(Episode::factory(2)->state(new Sequence(
            ['overview' => 'First episode overview', 'sort' => 1],
            ['overview' => 'Second episode overview', 'sort' => 2],
        )), 'episodes')
        ->create();

    $user = User::factory()->create();
    $user->courses()->attach($course);

    $nextEpisode = $course->episodes->last();

    Livewire::actingAs($user)->test(WatchEpisode::class, ['course' => $course])
        ->assertOk()
        ->dispatch('episode-ended', $course->episodes->first()->getRouteKey())
        ->assertRedirect("/courses/{$course->getRouteKey()}/episodes/{$nextEpisode->getRouteKey()}");
});

it('stays in the the last episode after video ends', function () {
    $course = Course::factory()
        ->has(Episode::factory(2)->state(new Sequence(
            ['overview' => 'First episode overview', 'sort' => 1],
            ['overview' => 'Second episode overview', 'sort' => 2],
        )), 'episodes')
        ->create();

    $user = User::factory()->create();
    $user->courses()->attach($course);

    Livewire::actingAs($user)->test(WatchEpisode::class, ['course' => $course, 'episode' => $course->episodes->last()->getRouteKey()])
        ->assertOk()
        ->dispatch('episode-ended', $course->episodes->last()->getRouteKey())
        ->assertSeeText('Second episode overview');
});

it('forbids showing episodes to users that do not own course', function () {
    $course = Course::factory()
        ->has(Episode::factory())
        ->create();
    $user = User::factory()->create();
    $stranger = User::factory()->create();

    $user->courses()->attach($user);

    Livewire::actingAs($stranger)->test(WatchEpisode::class, ['course' => $course])
        ->assertForbidden();
});

it('marks episode as watched after video ends', function () {
    $course = Course::factory()
        ->has(Episode::factory())
        ->create();

    $user = User::factory()->create();
    $user->courses()->attach($course);

    expect($user->watchedEpisodes)
        ->toHaveCount(0);
    
    Livewire::actingAs($user)->test(WatchEpisode::class, ['course' => $course])
        ->assertOk()
        ->dispatch('episode-ended', $course->episodes->first()->getRouteKey());
    
    $user->load('watchedEpisodes');

    expect($user->watchedEpisodes)
        ->toHaveCount(1);
});

it('marks episode as watched only once', function () {
    $course = Course::factory()
        ->has(Episode::factory())
        ->create();

    $user = User::factory()->create();
    $user->courses()->attach($course);

    expect($user->watchedEpisodes)
        ->toHaveCount(0);
    
    Livewire::actingAs($user)->test(WatchEpisode::class, ['course' => $course])
        ->assertOk()
        ->dispatch('episode-ended', $course->episodes->first()->getRouteKey())
        ->dispatch('episode-ended', $course->episodes->first()->getRouteKey());
    
    $user->load('watchedEpisodes');
    
    expect($user->watchedEpisodes)
        ->toHaveCount(1);
});
