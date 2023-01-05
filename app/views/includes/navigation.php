<nav class="top-nav">
    <ul>
        <li>
            <a href="<?php echo URLROOT; ?>/index" style="font-size: 30px;"><i class="fa fa-solid fa-book-open-reader"></i> Notes </a>
        </li>
        <li>
            <a href="<?php echo URLROOT; ?>/notifications/calendar">Calendar</a>
        </li>
        <li>
            <a href="<?php echo URLROOT; ?>/users/register">Register</a>
        </li>
        <li class="btn-login">
            <?php if (isset($_SESSION['user_id'])) : ?>
                <a href="<?php echo URLROOT; ?>/users/logout">Log out</a>
            <?php else : ?>
                <a href="<?php echo URLROOT; ?>/users/login">Login</a>
            <?php endif; ?>
        </li>
    </ul>
</nav>