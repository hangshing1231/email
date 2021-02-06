<?php

namespace Tests\Feature;
use Tests\TestCase;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SendEmail;

class EmailJobTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSendOneEmailTest()
    {
        $testEmail = 'hangshing1231@gmail.com';
        Queue::fake();
        $response = $this->post(
            '/api/send?api_token=test-token',
            [
               'data' => [
                    [
                        'to'            => $testEmail,
                        'subject'       => 'test subject',
                        'body'          => 'test body'
                    ]
               ]
            ]
        );

        Queue::assertPushed(SendEmail::class, 1);

        $response = $this->post(
            '/api/send?api_token=test-token',
            [
               'data' => [
                    [
                        'to'            => $testEmail,
                        'subject'       => 'test subject',
                        'body'          => ''
                    ]
               ]
            ]
        );

        Queue::assertPushed(SendEmail::class, 1);

        $response = $this->post(
            '/api/send?api_token=test-token',
            [
               'data' => [
                    [
                        'to'            => $testEmail,
                        'subject'       => '',
                        'body'          => 'test body'
                    ]
               ]
            ]
        );

        Queue::assertPushed(SendEmail::class, 1);

        $response = $this->post(
            '/api/send?api_token=test-token',
            [
               'data' => [
                    [
                        'to'            => '',
                        'subject'       => 'test subject',
                        'body'          => 'test body'
                    ]
               ]
            ]
        );

        Queue::assertPushed(SendEmail::class, 1);

        $response = $this->post(
            '/api/send?api_token=test-token',
            [
               'data' => [
                    [
                        'to'            => $testEmail,
                        'subject'       => 'test subject',
                        'body'          => '',
                        'attachments'   => [
                            [
                                'file'  => 'data:text/plain;base64,c2Zkc2Q=',
                                'name'  => ''
                            ]
                        ]
                    ]
               ]
            ]
        );
        
        Queue::assertPushed(SendEmail::class, 1);

        $response = $this->post(
            '/api/send?api_token=test-token',
            [
               'data' => [
                    [
                        'to'            => $testEmail,
                        'subject'       => 'test subject',
                        'body'          => 'test body',
                        'attachments'   => [
                            [
                                'file'  => '',
                                'name'  => 'test1.png'
                            ]
                        ]
                    ]
               ]
            ]
        );

        Queue::assertPushed(SendEmail::class, 1);

        $response = $this->post(
            '/api/send?api_token=test-token',
            [
               'data' => [
                    [
                        'to'        => $testEmail,
                        'subject'   => 'test subject',
                        'body'      => 'test body',
                        'attachments'   => [
                            [
                                'file'  => 'data:text/plain;base64,c2Zkc2Q=',
                                'name'  => 'test2.txt'
                            ]
                        ]
                    ]
               ]
            ]
        );

        Queue::assertPushed(SendEmail::class, 2);
    }

    public function testSendMultipleEmailsTest()
    {
        $testEmail = 'hangshing1231@gmail.com';
        Queue::fake();
        $response = $this->post(
            '/api/send?api_token=test-token',
            [
               'data' => [
                    [
                        'to'            => $testEmail,
                        'subject'       => 'test subject',
                        'body'          => 'test body'
                    ],
                    [
                        'to'            => '',
                        'subject'       => 'test subject',
                        'body'          => 'test body'
                    ],
                    [
                        'to'            => $testEmail,
                        'subject'       => '',
                        'body'          => 'test body'
                    ],
                    [
                        'to'            => $testEmail,
                        'subject'       => 'test subject',
                        'body'          => ''
                    ],
                    [
                        'to'            => $testEmail,
                        'subject'       => 'test subject',
                        'body'          => '',
                        'attachments'   => [
                            [
                                'file'  => 'data:text/plain;base64,c2Zkc2Q=',
                                'name'  => ''
                            ]
                        ]
                    ],
                    [
                        'to'            => $testEmail,
                        'subject'       => 'test subject',
                        'body'          => 'test body',
                        'attachments'   => [
                            [
                                'file'  => '',
                                'name'  => 'test1.png'
                            ]
                        ]
                    ],
                    [
                        'to'        => $testEmail,
                        'subject'   => 'test subject',
                        'body'      => 'test body',
                        'attachments'   => [
                            [
                                'file'  => 'data:text/plain;base64,c2Zkc2Q=',
                                'name'  => 'test2.txt'
                            ]
                        ]
                    ]
               ]
            ]
        );
        
        Queue::assertPushed(SendEmail::class, 2);
    }
}
