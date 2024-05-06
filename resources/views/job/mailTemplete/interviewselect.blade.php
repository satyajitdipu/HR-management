<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application select For interview</title>
    <style>
        /* Reset CSS */
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 16px;
            line-height: 1.5;
        }
        img {
            max-width: 100%;
            height: auto;
        }
        /* Email wrapper */
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
            border-radius: 10px;
        }
        /* Email header */
        .email-header {
            text-align: center;
            margin-bottom: 20px;
        }
        /* Email content */
        .email-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
        }
        /* Email footer */
        .email-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
        /* Button style */
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #f43b48;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        /* Button hover style */
        .btn:hover {
            background-color: #d63031;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
        <img src="https://cdn.nettantra.net/wp-content/uploads/2019/11/hub-nettantra-logo-2019.png" alt="Company Logo">
        <div class="email-header">
    <h2>Application select For interview</h2>
</div>
<div class="email-content">
    <p>Dear {{$status->name}},</p>
    <p>Congratulations! We are pleased to inform you that you have been selected  for the position of {{$status->job_title}} for interview. Welcome to our team!</p>
    <p>We appreciate your interest in our company and the enthusiasm you showed during the application process.</p>
    <p>If you have any questions or need further information, please feel free to reach out to us.</p>
    <p>Plese select timing your interview that after some provided available slot </p>
    <p>Best regards,</p>
    <p>The Hiring Team</p>
</div>

        <div class="email-footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <a href="https://www.nettantra.com" class="btn">Visit our website</a>
        </div>
    </div>
</body>
</html>
