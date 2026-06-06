<?php

namespace Tests\Feature;

use App\Http\Resources\SongResource;
use App\Models\Artist;
use App\Models\Interaction;
use App\Models\Song;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

use function Tests\create_user;

class ArtistSongTest extends TestCase
{
    #[Test]
    public function index(): void
    {
        $artist = Artist::factory()->createOne();

        Song::factory()->for($artist)->createMany(5);

        $this->getAs("api/artists/{$artist->id}/songs")->assertJsonStructure([0 => SongResource::JSON_STRUCTURE]);
    }

    #[Test]
    public function indexIncludesUserPlayCount(): void
    {
        $user = create_user();
        $artist = Artist::factory()->createOne();
        $song = Song::factory()->for($artist)->createOne();

        Interaction::factory()->for($user)->for($song)->createOne(['play_count' => 12]);

        $this->getAs("api/artists/{$artist->id}/songs", $user)
            ->assertJsonFragment([
                'id' => $song->id,
                'play_count' => 12,
            ]);
    }
}
