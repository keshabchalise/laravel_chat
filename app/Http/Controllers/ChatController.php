<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        return view('chat');
    }

    public function getMessages(){
        return Message::with('user')->get();
    }

    public function sendMessage(Request $request){

        $user = Auth::user();

        $message = $user->messages()->create(['message'=>$request->message]);

        broadcast(new MessageSent($user,$message))->toOthers();

        return ['status'=>'message sent'];
    }
}
