<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="{{ asset('/css/sentlist.css') }}">
</head>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <body class="antialiased">
        <div>
            <form>
                <table>
                    <tr>
                        <td>Email to</td>
                        <td>Subject</td>
                        <td>Body</td>
                        <td>Attachments</td>
                    </tr>
                    @foreach ($mails as $mail)
                        <tr>
                            <td>{{ $mail->to }}</td>
                            <td>{{ $mail->subject }}</td>
                            <td>{{ $mail->body }}</td>
                            <td>
                                @if (!empty($mail->attachments))
                                        @foreach ($mail->attachments as $attachment)
                                            <a href="download/{{ $attachment->id }}?api_token=test-token">{{$attachment->name}}</a>
                                            <br>
                                        @endforeach
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </table>
            </form>
        </div>
    </body>
</html>
