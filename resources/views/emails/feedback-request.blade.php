<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Feedback Request</title>
</head>

<body
    style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -webkit-text-size-adjust: none; background-color: #ffffff; color: rgb(0, 0, 0); height: 100%; line-height: 1.4; margin: 0; padding: 0; width: 100% !important; font-size: 16px;">

    <p>Dear {{ $applicant->first_name }},</p>

    <p>Thank you for attending your recent interview with us for the
        <strong>{{ $applicant->jobPosition->position_title }}</strong> position.
    </p>

    <p>We’d appreciate it if you could take a few minutes to fill out our feedback form. Your input helps us improve our
        process.</p>
    <br>
    <p>
        <a href="{{ route('feedback.create', $interviewScheduleId) }}"
            style="background-color: #763b88; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">
            Fill Out Feedback Form
        </a>
    </p>

    <p>Best regards,<br>
        HR Department<br>
        hr@chimesconsulting.com<br>
        Chimes Consulting</p>
</body>

</html>
