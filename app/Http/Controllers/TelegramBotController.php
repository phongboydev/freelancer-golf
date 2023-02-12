<?php
/**
 * Author: bavuong0810@gmail.com
 * Date: 06/12/2022
 * Time: 16:01 PM
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Telegram\Bot\Laravel\Facades\Telegram;

class TelegramBotController extends Controller
{
    public function updatedActivity()
    {
        $activity = Telegram::getUpdates();
        dd($activity);
    }
}
