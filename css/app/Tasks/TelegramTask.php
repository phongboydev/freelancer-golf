<?php

namespace App\Tasks;

use InApps\IAModules\Helpers\LogHelper;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramTask
{
    public function sendMessage($text)
    {
        try {
            return Telegram::sendMessage([
                'chat_id' => env('TELEGRAM_CHANNEL_ID', ''),
                'parse_mode' => 'HTML',
                'text' => $text
            ]);
        } catch (\Exception $e) {
            LogHelper::debug(json_encode($e));
            return [
                'success' => false,
                'message' => 'Fail to send message to Telegram.'
            ];
        }
    }
}
