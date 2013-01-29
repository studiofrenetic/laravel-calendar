<?php namespace Calendar;

// Base on https://github.com/dlpetrie/fuel_calendar


use Config;
use Lang;
use View;

class Calendar {

    private static $_instance = null;

    // config settings
    protected static $_view = 'month';
    protected static $_navigation = false;
    protected static $_navigation_url = null;
    protected static $_dates_as_links = true;
    protected static $_viewpath = null;
    
    //date settings
    protected static $_year;
    protected static $_month;
    protected static $_day;
    protected static $_week;
    protected static $_first_day;
    protected static $_days_in_month;
    protected static $_weeks_in_month;
    protected static $_data;
    
    /**
     * Return a new calendar object
     *
     * $data array key is the day of month
     * $data array value is used to display data in the day cell;
     *
     * @param array
     */
    public static function getInstance ()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     * Sets the initial calendar configuration settings
     *
     * @param array
     */
    private function __construct () { }

    

    public function render($view = null)
    {
        if($view)
        {
            self::$_view = $view;
        }
        else
        {
            self::$_view = 'Calendar::month';
        }

        if(str_contains(self::$_view, '::'))
        {
            list($bundle, $view) = explode('::', self::$_view);
        }

        
        
        if(str_contains($view, '/'))
        {
            $viewArr = explode('/', $view);
            $view = array_pop($viewArr);
        }

        if($view != 'week' and $view != 'day')
        {
            $view = 'month';
        }

        $build = 'build_'.$view;

        // set data
        $data = array(
            //dates
            'build'      => $build,
            'days'       => Lang::line('calendar::calendar.days'),
            'month'      => Lang::line('calendar::calendar.months.'.self::$_month),
            'year'       => self::$_year,
            'calendar'   => self::$build(),
            // //navigation
            'navigation' => self::$_navigation,
            'nav_next'   => (self::$_navigation) ? self::get_navigation('next') : null,
            'nav_prev'   => (self::$_navigation) ? self::get_navigation('previous') : null,
            'nav_month'  => (self::$_navigation) ? self::$_navigation_url.'month/'.$year.'/'.$month : null,
            'nav_week'   => (self::$_navigation) ? self::$_navigation_url.'week/'.$year.'/'.$month.'/'.self::get_week() : null,
            'nav_day'    => (self::$_navigation) ? self::$_navigation_url.'day/'.$year.'/'.$month.'/'.self::get_first_day() : null,
        );


        echo View::make(self::$_view, $data)->render();

    }
    
    /**
     * Builds the data array to display in view
     *
     * @param int
     * @param int
     * @param int
     * @param array
     */
    public static function build($year = null, $month = null, $day = null, $data = null)
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        // convert year and month to ints
        !is_int($year) and $year = (int)$year;
        !is_int($month) and $month = (int)$month;
        !is_int($day) and $day = (int)$day;
        
        // set main vars
        $year == null and $year = date('Y');
        $month == null and $month = date('n');
        $day == null and $day = date('j');
        
        // set values to closest date if date does not exist
        strlen($year) < 4 and $year = (int)str_pad($year, 4, 0, STR_PAD_RIGHT);
        strlen($year) > 4 and $year = (int)substr($year, 0, 4);
        $month > 12 and $month = 12;
        $month < 1 and $month = 1;
        $day > self::find_days_in_month($month, $year) and $day = self::find_days_in_month($month, $year);
        $day < 1 and $day = 1;
        
        // check date values
        if ($data and !is_array($data))
        {
            throw new \InvalidArgumentException('The data param only accepts arrays or null');
        }

        
        
        // set properties
        // $this->_view = $view;
        self::$_month          = $month;
        self::$_year           = $year;
        self::$_day            = $day;
        self::$_week           = $weekIndex =ceil(substr(date('Y-m-d', mktime(0,0,0,self::$_month,self::$_day,self::$_year)), -2) / 7);
        self::$_data           = $data;
        self::$_days_in_month  = self::find_days_in_month($month, $year);
        self::$_first_day      = self::find_first_weekday_of_month($month, $year);
        self::$_weeks_in_month = self::find_weeks_in_month(self::$_days_in_month, self::$_first_day);

        return self::$_instance;
    }
    
    /**
     * Builds a single month view
     */
    protected static function build_month()
    {
        $data = array();    
        
        //set vars for loop
        $day_of_month = 0;
        $week = 1;

        // start data loop
        while ($day_of_month <= self::$_days_in_month)
        {
            // loop through days in week - Sun = 1
            for( $day_of_week = 1; $day_of_week < 8; $day_of_week++)
            {
                // if add 1 to start month counter when week day = first day
                if ($day_of_week == self::$_first_day and $day_of_month == 0)
                {
                    $day_of_month++;
                }
                
                // month cells
                if ($day_of_month > 0 and $day_of_month <= self::$_days_in_month)
                {
                    if (self::$_dates_as_links and !isset(self::$_data[$day_of_month]['link']))
                    {
                        self::$_data[$day_of_month]['link'] = self::$_navigation_url.'day/'.self::$_year.'/'.self::$_month.'/'.$day_of_month;
                    }
                    $data[$week][$day_of_week] = array(
                        'date' => ($day_of_month == 0) ? null : $day_of_month,
                        'attributes' => isset(self::$_data[$day_of_month]['attributes']) ?  self::$_data[$day_of_month]['attributes'] : null,
                        'link' => isset(self::$_data[$day_of_month]['link']) ?  self::$_data[$day_of_month]['link'] : null,
                        'text' => isset(self::$_data[$day_of_month]['text']) ? self::$_data[$day_of_month]['text'] : null
                    );
                    $day_of_month++;
                }
                else // blank cells
                {
                    $data[$week][$day_of_week] = array(
                        'date' => null,
                        'attributes' => null,
                        'link' => null,
                        'text' => null,
                    );
                }
            }
            $week++;
        }
        
        return $data;
    }
    
    /**
     * Builds a single week view
     */
    protected static function build_week()
    {   
        $month = self::build_month();

        

        return $month[self::$_week];
    }
    
    protected static function build_day()
    {
        $data = array(
            'date' => self::$_day,
            'attributes' => isset(self::$_data[self::$_day]['attributes']) ?  self::$_data[self::$_day]['attributes'] : null,
            'link' => isset(self::$_data[self::$_day]['link']) ?  self::$_data[self::$_day]['link'] : null,
            'text' => isset(self::$_data[self::$_day]['text']) ? self::$_data[self::$_day]['text'] : null
        );
        
        return $data;
    }
    
    /**
     * Calculates how many days are in a given month
     *
     * @param int
     * @param int
     */
    public static function find_days_in_month($month, $year)
    {
        return cal_days_in_month(CAL_GREGORIAN, $month, $year); //date('t', mktime(0,0,0,$month,1,$year));
    }
    
    /**
     * Finds the first weekday of the month
     *
     * @param int
     * @param int
     */
    public static function find_first_weekday_of_month($month, $year)
    {
        return date('w', mktime(0,0,0,$month,1,$year)) + 1;
    }
    
    /**
     * Find # of weeks in a month
     *
     */
    protected static function find_weeks_in_month($days_in_month, $first_day)
    {
        $number_of_days = $days_in_month - ( 7 - ($first_day-1) );
        $number_of_weeks = (1 + (int)( $number_of_days / 7));
        if ($number_of_days % 7 > 0) $number_of_weeks++;
        
        return $number_of_weeks;
    }
    
    /**
     * Builds navigation and checks dates
     *
     * @param string
     */
    protected static function get_navigation($direction)
    {
        if ($direction == 'next') //next week or month
        {
            if (self::$_view == 'week')
            {
                $week = self::$_day + 1;
                $month = self::$_month;
                $year = self::$_year;
                if ($week > self::$_weeks_in_month)
                {
                    $week = 1;
                    $month++;
                    if($month > 12)
                    {
                        $month = 1;
                        $year++;
                    }
                }
                return self::$_navigation_url.self::$_view.'/'.$year.'/'.$month.'/'.$week;
            }
            else if (self::$_view == 'day')
            {
                $day = self::$_day + 1;
                $month = self::$_month;
                $year = self::$_year;
                if ($day > self::$_days_in_month)
                {
                    $day = 1;
                    $month++;
                    if ($month > 12)
                    {
                        $month = 1;
                        $year++;
                    }
                }
                return self::$_navigation_url.self::$_view.'/'.$year.'/'.$month.'/'.$day;
            }
            else
            {
                // get next month
                $month = self::$_month + 1;
                $year = self::$_year;
                if ($month > 12)
                {
                    $month = 1;
                    $year++;
                }   
            }
            return self::$_navigation_url.self::$_view.'/'.$year.'/'.$month;
        }
        else //previous week or month
        {
            if (self::$_view == 'week')
            {
                $week = self::$_day - 1;
                $month = self::$_month;
                $year = self::$_year;
                if ($week <= 0)
                { 
                    $month--;
                    if ($month <= 0)
                    {
                        $month = 12;
                        $year--;
                    }
                    $week = self::find_weeks_in_month(self::find_days_in_month($month, $year), self::find_first_weekday_of_month($month, $year));
                }
                return self::$_navigation_url.self::$_view.'/'.$year.'/'.$month.'/'.$week;
            }
            else if (self::$_view == 'day')
            {
                $day = self::$_day - 1;
                $month = self::$_month;
                $year = self::$_year;
                if ($day <= 0)
                {
                    $month--;
                    if ($month <= 0)
                    {
                        $month = 12;
                        $year--;
                    }
                    $day = self::find_days_in_month($month, $year);
                }
                return self::$_navigation_url.self::$_view.'/'.$year.'/'.$month.'/'.$day;
            }
            else
            {
                $month = self::$_month - 1;
                $year = self::$_year;
                if($month <= 0)
                {
                    $month = 12;
                    $year--;
                }
                return self::$_navigation_url.self::$_view.'/'.$year.'/'.$month;
            }
        }
    }
    
    /**
     * Gets days of week
     */
    protected function get_days()
    {
        $days = array(
            '1' => 'Sunday',
            '2' => 'Monday',
            '3' => 'Tuesday',
            '4' => 'Wednesday',
            '5' => 'Thursday',
            '6' => 'Friday',
            '7' => 'Saturday'
        );
        
        return $days;
    }
    
    /**
     * Gets current month
     */
    protected function get_month()
    {
        $months = array(
            '1' => 'January',
            '2' => 'February',
            '3' => 'March',
            '4' => 'April',
            '5' => 'May',
            '6' => 'June',
            '7' => 'July',
            '8' => 'August',
            '9' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December'
        );
        
        return $months[self::$_month];
    }
    
    /**
     * Get first day of week or month
     */
    protected static function get_first_day()
    {
        if (self::$_view == 'month')
        {
            return 1;
        }
        else if (self::$_view == 'week')
        {
            $month = self::build_month();
            return $month[self::$_day][1]['date'];
        }
        return $this->_day_or_week;
    }
    
    /**
     * Get first week of month or current week of the selected day
     */
    protected static function get_week()
    {
        if (self::$_view == 'month')
        {
            return 1;
        }
        else if (self::$_view == 'day')
        {
            $month = self::build_month();
            foreach ($month as $week_num => $week)
            {
                foreach ($week as $day)
                {
                    if ($day['date'] == self::$_day)
                    {
                        return $week_num;
                    }
                }
            }
            return 1;
        }
        return self::$_day;
    }
}