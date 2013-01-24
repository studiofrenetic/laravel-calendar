<section class="fuel_calendar">
    <header>
        <h3><?php echo $month.' '.$year ?></h3>
        <?php if($navigation): ?>
        <nav>
            <ul>
                <li><a href="<?php echo $nav_prev; ?>" class="previous">Prev</a></li>
                <li><a href="<?php echo $nav_day; ?>"class="day">Day</a></li>
                <li><a href="<?php echo $nav_week; ?>"class="week">Week</a></li>
                <li><a href="<?php echo $nav_month; ?>" class="month selected">Month</a></li>
                <li><a href="<?php echo $nav_next; ?>" class="next">Next</a></li>
            </ul>
        </nav>
        <?php endif; ?>
    </header>
    <section>
        <table>
            <tr>
                <?php foreach($days as $name): ?>
                    <th><?php echo $name; ?></th>
                <?php endforeach; ?>
            </tr>
            <?php foreach($calendar as $week): ?>
            <tr>
                <?php foreach($week as $day): ?>
                    <td>
                        <?php if($day['link']): ?>
                            <a href="<?php echo $day['link']; ?>"><?php echo $day['date']; ?></a>
                        <?php else: ?>
                            <?php echo $day['date']; ?>
                        <?php endif; ?>
                        <?php echo $day['text']; ?>
                    </td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
        </table>
    </section>
</section>