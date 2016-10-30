<?php

use Illuminate\Http\Request;
use Vluzrmos\SlackApi\Contracts\SlackTeam;
use Vluzrmos\SlackApi\Contracts\SlackChannel;
use Vluzrmos\SlackApi\Contracts\SlackUserAdmin;

$app->get('/', function (SlackTeam $slackTeam, SlackChannel $slackChannel) use ($app) {
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
});

$app->post('/invite', function(Request $request, SlackUserAdmin $slackUserAdmin) use ($app) {
    $invitation = $slackUserAdmin->invite($request->get('email'));
    if($invitation->ok === false) {
        return view('error');
    }

    return view('success');
});
