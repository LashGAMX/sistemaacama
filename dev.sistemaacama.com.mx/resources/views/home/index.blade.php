<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <form action="{{url('/home/create')}}" method="POST">
        @csrf
        <input type="text" class="" name="name">
        <input type="text" class="" name="last">
        <button class="" type="submit">Crear</button>
    </form>
  
</body>
</html>