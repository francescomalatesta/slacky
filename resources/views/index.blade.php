<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ trans('lines.page_title', ['teamName' => $teamName]) }}</title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="{{ url('style.css') }}">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>
        <div class="container">
            <div class="row" id="form_container">
                <div class="col-md-6 col-md-offset-3">
                    <form id="request_form">
                        <img src="{{ url('slack.png') }}" alt="Slack Logo" class="logo" />
                        <hr>
                        <h1>{{ trans('lines.claim') }}</h1>
                        <p>{!!  trans('lines.message', ['usersCount' => $usersCount, 'teamName' => $teamName]) !!}</p>
                        <hr>
                        <p><input type="text" class="form-control" id="email" placeholder="{{ trans('lines.email') }}" autofocus required /></p>
                        <hr>
                        <p><button type="submit" class="btn btn-success form-control" id="send_button"><b>{{ trans('lines.button.idle') }}</b></button></p>
                    </form>
                </div>
            </div>

            <div class="row" id="feedback"></div>
        </div>

        <div id="footer">
            {!! trans('lines.credits') !!}
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <script>
            $(document).ready(function(){
                $('#form_container').fadeIn(500);

                $('#request_form').submit(function(event){
                    $('#send_button').text('{{ trans('lines.button.sending') }}');
                    $('#send_button').prop('disabled', true);

                    $.post('{{ url('invite') }}', { email: $('#email').val() })
                        .always(function( data ) {
                            $('#send_button').prop('disabled', false);
                            $('#send_button').text('{{ trans('lines.button.idle') }}');

                            $('#feedback').html(data);
                        });

                    event.preventDefault();
                });
            });
        </script>
    </body>
</html>
