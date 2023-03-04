<?php

namespace App\Service;

use Twilio\Rest\Client;

class TwilioService

{
    private $client;

    public function __construct(string $accountSid, string $authToken)
    {
        $this->client = new Client($accountSid, $authToken);
    }

    public function sendSms(string $toPhoneNumber, string $message)
    {
        $this->client->messages->create($toPhoneNumber, [
            'from' => '+12765337052',
            'body' => $message
        ]);
    }
}
