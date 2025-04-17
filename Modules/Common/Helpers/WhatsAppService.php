<?php

namespace Modules\Common\Helpers;

use UltraMsg\WhatsAppApi;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $client;
    protected $schoolSettings;
    public function __construct($schoolSettings)
    {
        // $token = env('ULTRAMSG_TOKEN');
        // $instanceId = env('ULTRAMSG_INSTANCE_ID');
        $token = $schoolSettings->ultramsg_token;
        $instanceId = $schoolSettings->ultramsg_instance_id;
        $this->client = new WhatsAppApi($token, $instanceId);
    }

    public function sendMessage($to, $message, $priority = 10, $referenceId = null)
    {
        try {
         $response = $this->client->sendChatMessage($to, $message, $priority, $referenceId);
            Log::info('WhatsApp message sent', ['to' => $to, 'response' => $response]);
        } catch (\Exception $e) {
            Log::error('WhatsApp message failed', ['error' => $e->getMessage()]);
        }
    }
}