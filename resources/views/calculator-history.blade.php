<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<div>
    <h4>История вычислений</h4>
    <ul>
        @foreach (session('history', []) as $item)
            <li>{{ $item }}</li>
        @endforeach
    </ul>
</div>
</body>
</html>

