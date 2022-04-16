<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<style>
    * {
    padding: 0%;
    margin: 0%;
    box-sizing: border-box;
}

body {
    height: 100vh;
    display: grid;
    place-items: center;
}

.content {
    text-align: center;
}

.content .bg-h1 {
    background-image: url('{{asset("assets/images/Beautiful-Stars-Pic.jpg")}}');
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
    font-size: 10rem;
    font-weight: bold;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    font-family: 'Steelfish Rg', 'helvetica neue', helvetica, arial, sans-serif;
}

.content p:nth-child(2) {
    font-weight: bold;
    font-size: 1.2rem;
}

.content p:nth-child(3) {
    width: 50%;
    margin: 0 auto;
}
</style>

<body>
    <div class="content">
        <h1 class="bg-h1">Oops!</h1>
        <p>404 - PAGE NOT FOUND</p>
        <p>The page you are looking for might have been removed had its name changed or temporarily unavailable</p>
        <a href="" class="btn btn-primary mt-3" id="sendBack">Go to home page</a>
    </div>


    <script>
        document.querySelector('#sendBack').onclick = function(){
            window.history.back();
        }
    </script>
</body>

</html>