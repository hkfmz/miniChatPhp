<?php
session_start();
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <title>Hegel-TPchat-server</title>

    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/style.css" />

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/code.js"></script>

</head>
<body>
  <div class="wrapper">
        <?php
        if (isset($_SESSION['userName'])) {
            include_once 'view/chat.php';
        } else {
            include_once 'view/login.php';
        }
        ?>
  </div>
<footer>
    <a href="https://www.facebook.com/appEnligne/">Developed By Hegel Motokoua</a>
</footer>
</body>
</html>

<!--By Hegel Motokoua-->