<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application Confirmation</title>
    <style>
        /* Reset CSS */
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        img {
            max-width: 100%;
            height: auto;
        }
        /* Container style */
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        /* Heading style */
        h2 {
            color: #333;
            text-align: center;
        }
        /* List style */
        ul {
            list-style-type: none;
            padding: 0;
        }
        /* List item style */
        li {
            margin-bottom: 10px;
        }
        /* Logo wrapper style */
        .logo-wrapper {
            text-align: center;
            margin-bottom: 20px;
        }
        /* Logo image style */
        .logo-img {
            max-width: 200px;
        }
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
    <div class="container">
        <!-- Logo wrapper -->
        <div class="logo-wrapper">
            <!-- Logo image -->
            <img src="https://cdn.nettantra.net/wp-content/uploads/2019/11/hub-nettantra-logo-2019.png" alt="Company Logo" class="logo-img">
        </div>
        <!-- Heading -->
        <h2>Job Application Received</h2>
        <!-- Paragraphs and lists -->
        <p>Dear {{ $apply_job->name }},</p>
        <p>We are writing to confirm that we have received your application for the following position:</p>
        <ul>
            <li><strong>Job Title:</strong> {{ $apply_job->job_title }}</li>
        </ul>
        <p>Below are the details you provided:</p>
        <ul>
            <li><strong>Applicant Name:</strong> {{ $apply_job->name }}</li>
            <li><strong>Phone:</strong> {{ $apply_job->phone }}</li>
            <li><strong>Email:</strong> {{ $apply_job->email }}</li>
            <li><strong>Message:</strong> {{ $apply_job->message }}</li>
        </ul>
        <p>We appreciate your interest in our company and will review your application carefully.</p>
        <p>Thank you for considering us as your potential employer.</p>
        <p>Sincerely,</p>
        <p>The Hiring Team</p>
    </div>
    <div class="email-footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <a href="https://www.nettantra.com" class="btn">Visit our website</a>
        </div>
</body>
</html>
