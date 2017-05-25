<?php

namespace Tests\Feature\api\Auth;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class bindAccountTest extends TestCase
{
    use WithoutMiddleware;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testResponse()
    {
        $response = $this->json('POST',
                    '/api/account/bind', 
                    [
                        'mobile' => '18111630102',
                        'password' => '8345da691044475392e420472f1e308d',
                        'openid' => 'oNeiRwVJhYHiLzy4nrrQ2ydhXXYs',
                        'type' => 'wechat',
                    ]
        );
        $response
            ->assertStatus(200)
            ->assertExactJson([
                'status' => true,
            ]);
    }
}
