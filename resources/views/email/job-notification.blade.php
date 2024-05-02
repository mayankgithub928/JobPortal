<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Notification</title>
</head>
<body>
    <h1> Hello {{ $mailData['employer']->name }} </h1>
    <p>Job Title: {{ $mailData['job']->title }} </p>

    <p>User Name: {{ $mailData['user']->name }} </p>
    <p>User Email: {{ $mailData['user']->email }} </p>
    <p>Mobile: {{ $mailData['user']->mobile }} </p>
</body>
</html>