<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedule Table</title>
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
        /* Table style */
        table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        .date-column {
            width: 50%;
        }
        .time-column {
            width: 50%;
        }
        /* Button style */
        .btn-wrapper {
            text-align: center;
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #f43b48;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
        /* Button hover style */
        .btn:hover {
            background-color: #d63031;
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
        <!-- Logo -->
        <img src="https://cdn.nettantra.net/wp-content/uploads/2019/11/hub-nettantra-logo-2019.png" alt="Company Logo">
        <h2>Schedule Table</h2>
    </div>

    <table>
        <thead>
            <tr>
                <th class="date-column">Date</th>
                <th class="time-column">Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($scheduleData as $schedule)
            <tr>
                <td class="date-column">{{ $schedule['date'] ?? '' }}</td>
                <td class="time-column">{{ $schedule['time'] ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="btn-wrapper">
        <!-- Button to open website -->
        <a href="http://127.0.0.1:8000/user/dashboard/interviewing" class="btn">Select timing</a>
    </div>
</div>
<div class="email-footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <a href="https://www.nettantra.com" class="btn">Visit our website</a>
        </div>
</body>
</html>
