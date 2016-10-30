<?php

use Vluzrmos\SlackApi\Contracts\SlackTeam;
use Vluzrmos\SlackApi\Contracts\SlackChannel;

$app->get('/', function (SlackTeam $slackTeam, SlackChannel $slackChannel) use ($app) {
    $teamName = $slackTeam->info()->team->name;

    $channels = $slackChannel->all(true)->channels;
    $channels = array_filter($channels, function($channel){
        return ($channel->name === 'general') ? true : false;
    });
    $usersCount = count(reset($channels)->members);

    return view('index', compact('teamName', 'usersCount'));
});

$app->post('/invite', function() use ($app) {
    // TODO: implement logic
});
