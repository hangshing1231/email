<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Str;

class listSentEmailTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetListTest()
    {
        $testEmail = 'hangshing1231@gmail.com';
        $subject = Str::random($length = 10);
        $subject1 = Str::random($length = 10);

        $body = Str::random($length = 10);
        $body2 = Str::random($length = 10);

        $resp = $this->post(
            '/api/send?api_token=test-token',
            [
               'data' => [
                    [
                        'to'            => $testEmail,
                        'subject'       => $subject,
                        'body'          => $body
                    ],
                    [
                        'to'            => $testEmail,
                        'subject'       => '',
                        'body'          => $body2
                    ],
                    [
                        'to'            => $testEmail,
                        'subject'       => $subject1,
                        'body'          => ''
                    ],
               ]
            ]
        );

        sleep(10);

        $response = $this->get('/api/list');
        $response->assertStatus(511);
        
        $response = $this->get('/api/list?api_token=test-token');
        $response->assertStatus(200);

        $response->assertSee($subject);
        $response->assertSee($body);
        
        $response->assertDontSee($body2);
        $response->assertDontSee($subject1);

        $subject2 = Str::random($length = 10);
        $subject3 = Str::random($length = 10);
        $body3 = Str::random($length = 10);
        $body4 = Str::random($length = 10);
        $filename = Str::random($length = 10);
        $filename2 = Str::random($length = 10);

        $resp = $this->post(
            '/api/send?api_token=test-token',
            [
               'data' => [
                    [
                        'to'        => $testEmail,
                        'subject'   => $subject2,
                        'body'      => $body3,
                        'attachments'   => [
                            [
                                'file'  => 'data:text/plain;base64,c2Zkc2Q=',
                                'name'  => $filename
                            ]
                        ]
                    ],
                    [
                        'to'        => 'test@gmail.com',
                        'subject'   => $subject3,
                        'body'      => $body4,
                        'attachments'   => [
                            [
                                'file'  => '',
                                'name'  => $filename2
                            ]
                        ]
                    ]
               ]
            ]
        );

        sleep(10);
        $response = $this->get('/api/list?api_token=test-token');
        $response->assertStatus(200);

        $response->assertSee($subject2);
        $response->assertSee($body3);
        $response->assertSee($filename);

        $response->assertDontSee($subject3);
        $response->assertDontSee($body4);
        $response->assertDontSee($filename2);
    }
}
