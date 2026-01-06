<body
    style="box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol'; position: relative; -webkit-text-size-adjust: none; background-color: #ffffff; color: rgb(0, 0, 0); height: 100%; line-height: 1.4; margin: 0; padding: 0; width: 100% !important; font-size: 16px;">

    <!-- Dynamic Data & Header-->
    <p>Greetings, {{ $candidate->first_name }}</p>

    <!-- Content  -->
    <p>Congratulations! We are thrilled to inform you that you have been hired for the position you applied for at
        Chimes Consulting.</p>

    <!-- Dynamic Data -->
    <p>🗓️ Start Date: {{ $hiringDate }}<br>
        🏢 Department: {{ $department }}</p>
    <!-- Additional Information -->
    <p>Welcome aboard! We're excited to have you as part of our growing team. Further instructions will be sent to your
        email shortly.</p>
    <!-- Footer -->
    <p>Best regards,<br>
        HR Department<br>
        hr@chimesconsulting.com<br>
        Chimes Consulting</p>
</body>
