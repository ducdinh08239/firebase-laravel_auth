<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        a {
            text-decoration: none;
            color: black;
        }
    </style>
</head>
<body>
   <img src="{{Auth::user()->image_url}}" alt="">Hello, {{Auth::user()->name}} ({{Auth::user()->email}})
   <br>
   <button>
    <a href="{{route('logout')}}">Logout</a>
   </button>
</body>
</html>