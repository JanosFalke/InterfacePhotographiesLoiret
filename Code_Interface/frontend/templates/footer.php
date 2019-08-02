<footer>

    <?php
    $slashs = substr_count($_SERVER['REQUEST_URI'], '/');
    $link = '';

    for ($i = 0; $i < $slashs-1; $i++) {
        $link = $link.'../';
    }
    $link = $link.'backend/account/logout_handling.php';
    ?>

    <div>
    </div>

    <div>
        <p>Michael Adalbert & Janos Falke © 2019</p>
    </div>

    <div>
        <a href="<?php echo $link;?>">Logout</a>
    </div>
</footer>