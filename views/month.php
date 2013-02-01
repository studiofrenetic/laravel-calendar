<?php /* ?>
<table class="table table-bordered table-striped table-calendar">
    <caption>
        
    </caption>
    <thead>
        <tr>
          <th colspan="7" >
            <a class="btn"><i class="icon-chevron-left"></i></a>
            <a class="btn"><?php echo $month.' '.$year ?></a>
            <a class="btn"><i class="icon-chevron-right"></i></a>
          </th>
        </tr>
        <tr>
            <?php foreach($days as $name): ?>
                <th><?php echo $name; ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="muted">29</td>
            <td class="muted">30</td>
            <td class="muted">31</td>
            <td>1</td>
            <td>2</td>
            <td>3</td>
            <td>4</td>
        </tr>
        <tr>
            <td>5</td>
            <td>6</td>
            <td>7</td>
            <td>8</td>
            <td>9</td>
            <td>10</td>
            <td>11</td>
        </tr>
        <tr>
            <td>12</td>
            <td>13</td>
            <td>14</td>
            <td>15</td>
            <td>16</td>
            <td>17</td>
            <td>18</td>
        </tr>
        <tr>
            <td>19</td>
            <td><strong>20</strong></td>
            <td>21</td>
            <td>22</td>
            <td>23</td>
            <td>24</td>
            <td>25</td>
        </tr>
        <tr>
            <td>26</td>
            <td>27</td>
            <td>28</td>
            <td>29</td>
            <td class="muted">1</td>
            <td class="muted">2</td>
            <td class="muted">3</td>
        </tr>
    </tbody>
</table>
<?php */ ?>


<table class="table table-bordered table-striped table-calendar">
    <caption>
        <div class="alert">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          Cliquez sur une date pour ajouter un rappel ou consulter un rappel existant.
        </div>
    </caption>
    <thead>
        <tr>
          <th colspan="7" >
            <a class="btn"><i class="icon-chevron-left"></i></a>
            <a class="btn"><?php echo $month.' '.$year ?></a>
            <a class="btn"><i class="icon-chevron-right"></i></a>
          </th>
        </tr>
        <tr>
            <?php foreach($days as $name): ?>
                <th><?php echo $name; ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach($calendar as $week): ?>
            <tr>
                <?php foreach($week as $day): ?>
                    <?php
                    // day background color
                    if($day['date'] == 18) :
                        $day_class = 'error';
                    elseif ($day['date'] == date('j')):
                        $day_class = 'info';
                    else:
                        $day_class = '';
                    endif;
                    ?>
                    <td class="<?php echo (isset($day_class)) ? $day_class : ''; ?>">
                        <?php if($day['link']): ?>
                            <a href="<?php echo $day['link']; ?>"></a>
                            <div style="position:relative;">
                            <a class="btn-link dropdown-toggle" href="#" data-toggle="dropdown"><?php echo $day['date']; ?></a>
                            <div class="dropdown-menu" style="padding: 15px; width:450px;">
                                <form method="post" action="login" accept-charset="UTF-8">
                                    <div class="control-group">
                                        <a href="" class="btn btn-primary btn-small pull-right"><i class="icon icon-circle-arrow-up"></i> Ajouter</a>
                                        <?php echo Form::select('appointment[status][status]', array('1' => 'Client XYZ', 2 => 'Client Auto Machin Truc', 3 => 'Client 345'), 1, array('class' => 'input-xlarge')); ?>
                                    </div>
                                    <?php echo Form::textarea('reminder[note]', null, array('rows' => 5, 'placeholder' => __('key.reminder.note'), 'class' => 'span12 no-wysiwyg resize-vertical')); ?>
                                </form>
                            </div>
                            </div>
                        <?php else: ?>
                            <?php echo $day['date']; ?>
                        <?php endif; ?>
                            <p><small><?php echo $day['text']; ?></small></p>
                    </td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php /* ?>
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
<?php */ ?>