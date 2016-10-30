<?php

namespace App\Console;

use GuzzleHttp\Client;
use Illuminate\Console\Command;

class SetupCommand extends Command
{
    protected $name = 'slacky-setup';
    protected $description = "Sets the Slack access token.";

    public function fire()
    {
        if(env('SLACK_TOKEN')) {
            $this->output->success('It seems that you have already specified a Slack access token. Well done!');
            return;
        }

        $this->output->writeln('Welcome to Slacky!');

        $isTokenValid = false;
        $accessToken = '';
        while(!$isTokenValid) {
            $accessToken = $this->output->askHidden('Please, insert your slack access token');
            if($this->connectsSuccessfullyToSlackWith($accessToken)) {
                $isTokenValid = true;
            } else {
                $this->error('Mmmh... it seems that the token you provided is not valid. Try again.');
            }
        }

        $this->updateEnvFile($accessToken);
        $this->output->success('Token set successfully!');
    }

    private function connectsSuccessfullyToSlackWith($accessToken)
    {
        $client = new Client();

        $response = $client->get('https://slack.com/api/api.test', ['query' => ['token' => $accessToken]]);
        $response = json_decode($response->getBody());

        return $response->ok;
    }

    private function updateEnvFile($accessToken)
    {
        $lines = file('.env');
        $lines = array_map(function($item) use ($accessToken) {
            if(trim($item) === 'SLACK_TOKEN=') {
                return 'SLACK_TOKEN=' . $accessToken . "\n";
            }
            return $item;
        }, $lines);

        file_put_contents('.env', implode($lines));
    }
}
