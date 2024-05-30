<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    <h1>Contact Message</h1>
    <p><strong>Name:</strong> {{ $contact['name'] }}</p>
    <p><strong>Email:</strong> {{ $contact['email'] }}</p>
    <p><strong>Phone:</strong> {{ $contact['phone'] }}</p>
    <p><strong>Property URL:</strong> <a href="{{ $contact['property_url'] }}">{{ $contact['property_url'] }}</a></p>
</body>
</html>
