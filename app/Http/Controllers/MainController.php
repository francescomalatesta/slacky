<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Vluzrmos\SlackApi\Contracts\SlackTeam;
use Vluzrmos\SlackApi\Contracts\SlackChannel;
use Vluzrmos\SlackApi\Contracts\SlackUserAdmin;
use Vluzrmos\SlackApi\SlackApi;

class MainController extends Controller
{
    public function getIndex(SlackTeam $slackTeam, SlackApi $slackApi)
    {
        $result = $slackTeam->info();
        if($result->ok === false) {
            return trans('lines.auth_error');
        }

        $teamName = $result->team->name;

        $channels = $slackApi->get('conversations.list')->channels;
        $channels = array_filter($channels, function($channel){
            return ($channel->name === 'general') ? true : false;
        });
        $usersCount = reset($channels)->num_members;

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
