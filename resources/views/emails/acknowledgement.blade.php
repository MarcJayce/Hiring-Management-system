<body
    style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -webkit-text-size-adjust: none; background-color: #ffffff; color: rgb(0, 0, 0); height: 100%; line-height: 1.4; margin: 0; padding: 0; width: 100% !important; font-size: 16px;">

    <p>Dear {{ $applicant->first_name }} {{ $applicant->last_name }},</p>

    <p>Thank you for completing the application form for the {{ $applicant->jobPosition->position_title }} position at
        Chimes Consulting. We’re pleased to confirm that we have received your application.</p>

    <p>Our recruitment team will carefully review your submission, and should your qualifications match our
        requirements, we will be in touch to discuss the next steps in the hiring process. Please allow us some time as
        we assess all applications.</p>
    <br>
    <p>We appreciate your interest in joining our team and your enthusiasm for being part of Chimes Consulting. If you
        have any questions in the meantime, feel free to reach out.
    </p>
    <p>Best regards,<br>
        HR Department<br>
        hr@chimesconsulting.com<br>
        Chimes Consulting</p>
</body>

</html>
