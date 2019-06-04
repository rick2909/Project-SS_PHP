<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Index</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <link rel="stylesheet" type="text/css" media="screen" href="main.css">
    <script src="main.js"></script> -->
</head>
<body>
    <h1>Het werkt!</h1>
    <p>Gefeliciteert de website is online!</p>
    <a href="./phpmyadmin/" >Go to phpMyAdmin</a>.<br>
    <a href="seeder.php" >Fill my database</a>.

    
    <form action="api.php" method="post">
    <input type="text" name="function" value="addOpmerking" />
    <input type="text" name="email" value="test@test.nl" />
    <input type="text" name="password" value="geheim123" />
    <input type="text" name="protocol" value="TEST PROTOCOL. is hiermee een bewijs" />
    <input type="text" name="opmerking" value="TEST OPMERKING!" />
    <input type="text" name="titel" value="Opmerking titel" />
    <input type="number" name="id" value="2" />
    <input type="submit">
</form>
</body>
</html>