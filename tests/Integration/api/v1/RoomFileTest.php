<?php

namespace Tests\Integration\api\v1;

use App\Models\Room;
use App\Services\BigBlueButton\LaravelHTTPClient;
use App\Services\MeetingService;
use Database\Seeders\ServerSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RoomFileTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Testing to start a meeting with a file on a BBB-Server
     */
    public function testStartMeetingWithFile()
    {
        Storage::fake('local');
        $room      = Room::factory()->create();
        $validFile = UploadedFile::fake()->create('document.pdf', config('bigbluebutton.max_filesize') * 1000 - 1, 'application/pdf');

        $response = $this->actingAs($room->owner)->postJson(route('api.v1.rooms.files.add', ['room'=>$room]), ['file' => $validFile]);
        $response->assertSuccessful();
        
        $this->actingAs($room->owner)->putJson(route('api.v1.rooms.files.update', ['room'=> $room, 'file' => $response->json('data.files.0.id')]), ['use_in_meeting'=>true])
            ->assertSuccessful();

        // Adding server(s)
        $this->seed(ServerSeeder::class);

        // Create server
        $response = $this->actingAs($room->owner)->getJson(route('api.v1.rooms.start', ['room'=>$room,'record_attendance' => 1]))
            ->assertSuccessful();
        $this->assertIsString($response->json('url'));

        // Try to start bbb meeting
        $response = LaravelHTTPClient::httpClient()->withOptions(['allow_redirects' => false])->get($response->json('url'));
        $this->assertEquals(302, $response->status());
        $this->assertArrayHasKey('Location', $response->headers());

        // Clear
        (new MeetingService($room->runningMeeting()))->end();
    }
}
