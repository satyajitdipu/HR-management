<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer Letter</title>
    <style>
        /* Your CSS styles for the offer letter */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .offer-heading {
            text-align: center;
            margin-bottom: 20px;
        }
        .offer-details {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="offer-heading">
        <h1>Offer Letter</h1>
    </div>
    <div class="offer-details">
        <p>Dear {{ $data->user()->get()->first()->name }},</p>
        <p>We are pleased to offer you the position of {{ $data->job()->get()->first()->job_title  }} at nettantra. Your starting salary will be {{ $data->job()->get()->first()->salary_to }} per month.</p>
        <p>Please review this offer carefully and let us know if you have any questions. We look forward to your favorable reply and to welcoming you aboard.</p>
        <p>Sincerely,</p>
        <p>HR Team</p>
    </div>
</body>
</html>