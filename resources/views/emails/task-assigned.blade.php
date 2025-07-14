<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Task Assigned</title>
</head>
<body>
    <h2>Hello {{ $task->name }},</h2>
    <p>You have been assigned a new task:</p>
    <ul>
        <li><strong>Task Name:</strong> {{ $task->task_name }}</li>
        <li><strong>Status:</strong> {{ ucfirst($task->status) }}</li>
        <li><strong>Deadline:</strong> {{ \Carbon\Carbon::parse($task->deadline)->format('F j, Y') }}</li>
    </ul>
    <p>Please log in to the task manager to take action.</p>

    <p>Thanks,<br>Your Team</p>
</body>
</html>
