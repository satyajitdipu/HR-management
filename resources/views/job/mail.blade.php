<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        h2 {
            color: #333;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Job Application Received</h2>
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
</body>
</html>
