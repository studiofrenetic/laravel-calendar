<section class="fuel_calendar">
    <header>
        <h3><?php echo $month.' '.$year ?></h3>
        <?php if($navigation): ?>
        <nav>
            <ul>
                <li><a href="<?php echo $nav_prev; ?>" class="previous">Prev</a></li>
                <li><a href="<?php echo $nav_day; ?>"class="day selected">Day</a></li>
                <li><a href="<?php echo $nav_week; ?>"class="week">Week</a></li>
                <li><a href="<?php echo $nav_month; ?>" class="month">Month</a></li>
                <li><a href="<?php echo $nav_next; ?>" class="next">Next</a></li>
            </ul>
        </nav>
        <?php endif; ?>
    </header>
    <section>
        <?php echo $calendar['date']; ?><br />
        <?php echo $calendar['text']; ?>
    </section>
</section>