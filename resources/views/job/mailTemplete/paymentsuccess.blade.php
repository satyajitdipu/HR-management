<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <style>
        /* Reset CSS */
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }
        img {
            max-width: 100%;
            height: auto;
        }
        /* Container style */
        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        /* Heading style */
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        /* Paragraph style */
        p {
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
        /* List style */
        ul {
            list-style: none;
            padding: 0;
        }
        /* List item style */
        li {
            margin-bottom: 5px;
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
        <h2>Payment Successful</h2>
        <!-- Paragraphs -->
        <p>Dear {{ $payment->user()->get()->first()->name }},</p>
        <p>We are pleased to inform you that your payment was successful!</p>
        <p>Payment Details:</p>
        <!-- List of payment details -->
        <ul>
            <li><strong>Payment ID:</strong> {{ $payment->r_payment_id }}</li>
            <li><strong>Payment Method:</strong> {{ $payment->method }}</li>
            <li><strong>User Name:</strong> {{ $payment->user()->get()->first()->name }}</li>
            <li><strong>Amount:</strong> {{ $payment->amount }}</li>
        </ul>
        <p>Thank you for your payment. If you have any questions or concerns, please feel free to contact us.</p>
        <p>Best regards,</p>
        <p>The Payment Team</p>
        <div class="email-footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <a href="https://www.nettantra.com" class="btn">Visit our website</a>
        </div>
    </div>

</body>
</html>
