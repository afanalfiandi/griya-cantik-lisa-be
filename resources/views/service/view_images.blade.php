<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Images</title>
</head>
<body>
    <h1>Images for Service: {{ $service->serviceName }}</h1>

    <ul>
        @foreach($service->serviceDetail as $detail)
            <li>
                 <pre>{{ dd($detail) }}</pre>
                <img src="{{ asset($detail->img) }}" alt="Image" style="width: 200px;">
                <h1>Images for Service: {{ $detail->serviceID }}</h1>
            </li>
        @endforeach
    </ul>

</body>
</html>
