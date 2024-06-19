<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
    <h1>NOVO LEAD DA APB PARA VOCÊ!</h1>
    <p><strong>Cliente:</strong> {{ $contact['name'] }}</p>
    <p><strong>E-mail:</strong> {{ $contact['email'] }}</p>
    <p><strong>Telefone:</strong> {{ $contact['phone'] }}</p>
    <p><strong>Imóvel de interesse:</strong> <a href="{{ $contact['property_url'] }}">{{ $contact['property_url'] }}</a></p>
</body>
</html>
