<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Vluzrmos\SlackApi\Contracts\SlackTeam;
use Vluzrmos\SlackApi\Contracts\SlackChannel;
use Vluzrmos\SlackApi\Contracts\SlackUserAdmin;

class MainController extends Controller
{
    public function getIndex(SlackTeam $slackTeam, SlackChannel $slackChannel)
    {
        $result = $slackTeam->info();
        if($result->ok === false) {
            return trans('lines.auth_error');
        }

        $teamName = $result->team->name;

        $channels = $slackChannel->all(true)->channels;
        $channels = array_filter($channels, function($channel){
            return ($channel->name === 'general') ? true : false;
        });
        $usersCount = count(reset($channels)->members);

        return view('index', compact('teamName', 'usersCount'));
    }

    public function postIndex(Request $request, SlackUserAdmin $slackUserAdmin)
    {
        $invitation = $slackUserAdmin->invite($request->get('email'));
        if($invitation->ok === false) {
            $code = 'lines.errors.' . $invitation->error;
            $message = (trans($code) === $code) ? trans('lines.errors.generic') : trans($code);

            return view('error', compact('message'));
        }

        return view('success');
    }
}
