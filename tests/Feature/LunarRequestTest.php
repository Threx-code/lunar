<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LunarRequestTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_request_should_fail_when_no_shipping_parameter_is_provided()
    {
        $response = $this->postJson(route('lunar_time'), []);
        $response ->assertStatus(422)
        ->assertJson([
            'utc_date' => true,
        ]);
        $response->dump();
    }

    public function test_request_should_fail_when_wrong_shipping_time_is_provided()
    {
        $response = $this->postJson(route('lunar_time'), [
                'utc_date' => "2021-12-10"
            ]);
        $response ->assertStatus(422)
        ->assertJson([
                'utc_date' => true,
            ]);
        $response->assertJsonMissingValidationErrors([
            'utc_date'
        ]);

        $response->dump();

    }


    public function test_request_should_pass_when_a_UTC_datetime_is_provided()
    {
        $response = $this->postJson(route('lunar_time'), [
            'utc_date' => '2021-12-10 12:21:33'
        ]);
        $response->assertStatus(200)
        ->assertJson([
            'lunar_datetime' => true 
        ]);
        $response->assertOk();
        $response->dump();
        //$response->getContent();
    }
}
