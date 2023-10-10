<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
</head>

<body>
    <?php
    session_start();
    session_destroy();
    echo <<<data
    <script>
    window.history.back();
    </script>
    data;
    ?>
</body>

</html>