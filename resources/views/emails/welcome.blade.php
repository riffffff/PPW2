<!DOCTYPE html>
<html>
<head>
    <title>Welcome to Our Application</title>
</head>
<body>
    <h1>Hello, {{ $user->name }}!</h1>
    <p>Thank you for registering with us. Here is your registration information:</p>
    <ul>
        <li><strong>Name:</strong> {{ $user->name }}</li>
        <li><strong>Email:</strong> {{ $user->email }}</li>
        <li><strong>Registration Date:</strong> {{ $registrationDate }}</li>
    </ul>
    <p>We are excited to have you on board!</p>
    <p>Regards,<br>Our Team</p>
</body>
</html>
