<header>
    <nav>
        <ul>
            <?php foreach ($data as $title => $link): ?>
                <li><a href="<?php print $title; ?>"><?php print $link; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </nav>
</header>
