<?php

namespace App\Console;

use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SetupCommand extends Command
{
    protected $name = 'slacky-setup';
    protected $description = "Slacky setup command.";

    private $allowedLocales = ['en', 'it'];

    public function fire()
    {
        $this->output->writeln('Welcome to Slacky!');
        $this->askForSlackAccessToken();
        $this->askForLocale();
    }

    private function askForSlackAccessToken()
    {
        if(env('SLACK_TOKEN')) {
            $this->output->success('It seems that you have already specified a Slack access token. Well done!');
            return;
        }

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

        $this->updateEnvFileItem('SLACK_TOKEN', $accessToken);
        $this->output->success('Token set successfully!');
    }

    private function askForLocale()
    {
        $isLocaleValid = false;
        $chosenLocale = '';
        while(!$isLocaleValid) {
            $chosenLocale = $this->output->ask('Please specify a locale code ('.implode(', ', $this->allowedLocales).' are valid codes)', 'en');

            if(in_array($chosenLocale, $this->allowedLocales)) {
                $isLocaleValid = true;
            } else {
                $this->error('The specified locale was not found, try again!');
            }
        }

        $this->updateEnvFileItem('APP_LOCALE', $chosenLocale);
        $this->output->success('Locale set successfully!');
    }

    private function connectsSuccessfullyToSlackWith($accessToken)
    {
        $client = new Client();

        $response = $client->get('https://slack.com/api/api.test', ['query' => ['token' => $accessToken]]);
        $response = json_decode($response->getBody());

        return $response->ok;
    }

    private function updateEnvFileItem($name, $value)
    {
        $lines = file('.env');
        $lines = array_map(function($item) use ($name, $value) {
            if(Str::startsWith(trim($item), $name . '=')) {
                return $name . '=' . $value . "\n";
            }
            return $item;
        }, $lines);

        file_put_contents('.env', implode($lines));
    }
}
