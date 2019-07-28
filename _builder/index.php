<?php require_once ('_app/showContentFolder.php') ?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="_app/builder.css">
    <script src="../kernel/lib/js/jquery/jquery.js"></script>
    <script src="../templates/default/plugins/menumaker.js"></script>
    <title>BuilderPage</title>
</head>
<body>
    <main>
        <header>
            <h1>_builderPage</h1>
        </header>
        <article class="">
            <?php showContentFolder('./') ?>
        </article>
        <article class="">
            <img src="_app/about.jpg" alt="">
        </article>
        <footer></footer>
    </main>

</body>
</html>
