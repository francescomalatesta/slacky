<?php

return [
    'page_title' => ':teamName - Slacky!',

    'claim' => 'Hey, you!',
    'message' => '<b>:usersCount users</b> are waiting for you on the <b>:teamName!</b> team! Insert your email in the form below and you will immediately receive an invitation!',

    'email' => 'Your email address...',
    'button' => [
        'idle' => 'Join the Family',
        'sending' => 'Sending the Invitation...'
    ],

    'success' => 'Done! Check your inbox, an invitation is arriving!',

    'errors' => [
        'generic' => 'Mmmh... something went wrong. Try again later!',
        'invalid_email' => 'Please insert valid email address!',
        'already_invited' => 'It seems that an invitation for this email was already sent. Check your inbox!'
    ],

    'credits' => 'Crafted with <a href="https://lumen.laravel.com/" target="_blank">Lumen</a>, in a boring afternoon, by <a href="https://github.com/francescomalatesta" target="_blank">Francesco Malatesta</a>.',

    'auth_error' => 'Hey, it seems that I can\'t get in touch with your Slack team! Please, verify the access token in the .env file!'
];
