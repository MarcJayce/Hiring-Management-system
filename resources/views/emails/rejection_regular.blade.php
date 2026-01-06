<body
    style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -webkit-text-size-adjust: none; background-color: #ffffff; color: rgb(0, 0, 0); height: 100%; line-height: 1.4; margin: 0; padding: 0; width: 100% !important; font-size: 16px;">
    <p>Dear, {{ $candidate->first_name }} {{ $candidate->last_name }}</p>
    <!-- Content  -->
    <p>Thank you for your interest in the internship program at <strong>Chimes Consulting</strong>. and for the time you
        invested in your application for the {{ $candidate->jobPosition->position_title }} position.</p>
    <!-- Additional Information -->
    <p>After careful consideration, we regret to inform you that you have not been selected to move forward in the
        recruitment process. We received many strong applications, and making a final decision was not easy.</p>

    <p>We genuinely appreciate your desire to be part of our team and encourage you to apply for future openings that
        align with your skills and experience. Your qualifications are commendable, and we wish you all the best in your
        job search and professional journey.</p>

    <p>Thank you once again for considering Chimes Consulting.</p>

    <!-- Footer -->
    <p>Best regards,<br>
        HR Department<br>
        hr@chimesconsulting.com<br>
        Chimes Consulting</p>
</body>
