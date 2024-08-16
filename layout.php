<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>iBee</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="logo.jpeg" alt="Logo">
            <span>iBee</span>
        </div>
        <div class="sidebar-menu">
            <a href="index2.php"><i class="fas fa-home"></i> Home</a>
            <a href="sarang1.php"><i class="fas fa-beehive"></i> Sarang 1</a>
            <a href="sarang2.php"><i class="fas fa-beehive"></i> Sarang 2</a>
            <a href="sarang3.php"><i class="fas fa-beehive"></i> Sarang 3</a>
        </div>
    </div>
    <div class="content">
        <?php include($content); ?>
    </div>
</body>
</html>
