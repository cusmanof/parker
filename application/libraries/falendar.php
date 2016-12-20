<?php

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * based on calendar from Xu Ding
 */
class Falendar {

    /**
     * Constructor
     */
    public function __construct() {
        $this->naviHref = htmlentities($_SERVER['PHP_SELF']);
    }

    /*     * ******************* PROPERTY ******************* */

    private $dayLabels = array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");
    private $currentYear = 0;
    private $currentMonth = 0;
    private $currentDay = 0;
    private $currentDate = null;
    private $daysInMonth = 0;
    private $naviHref = null;
    private $today;
    private $first;
    private $used;
    private $free;
    private $isUser;
    private $user_model;

    /*     * ******************* PUBLIC ********************* */

    /**
     * print out the calendar
     */
    public function show($data) {
        $this->user_model =$data['user_model'];
        $this->today = date('Y-m-d');
        $this->isUser = $data['isUser'];
        $f = isset($data['content']['first_day']) ? $data['content']['first_day'] : null;
        $e = isset($data['content']['last_day']) ? $data['content']['last_day'] : null;
        if (empty($e))
            $e = $f;
        $this->first = min($f, $e);
        $this->last = max($f, $e);
        $this->used = isset($data['content']['used']) ? $data['content']['used'] : array();
        $this->free = isset($data['content']['free']) ? $data['content']['free'] : array();
        $year = $data['year'];

        $month = $data['month'];



        $this->currentYear = $year;

        $this->currentMonth = $month;

        $this->daysInMonth = $this->_daysInMonth($month, $year);

        $content = '<table id="fc" class="calendar">' .
                '<tr class="header">' . $this->_createNavi() . '</tr>' .
                '<tr class="day_header">' . $this->_createLabels() . '</tr>';
      
        $content .= '<div class="clear"></div>';
       

        $weeksInMonth = $this->_weeksInMonth($month, $year);
        // Create weeks in a month
        for ($i = 0; $i < $weeksInMonth; $i++) {

            //Create days in a week
            $content .= '<tr>';
            for ($j = 1; $j <= 7; $j++) {
                $content .= $this->_showDay($i * 7 + $j);
            }
            $content .= '</tr>';
        }

         $content .= '</table>';

      


        $content .= '<BR><center>';
        $curr_href = '&month=' . sprintf('%02d', $month) . '&year=' . $year;
        if (!empty($this->first)) {
            $content .= '<a  class="btn btn-large btn-info" href="' . $this->naviHref . '?act=clear' . $curr_href . '">Clear selection</a>';
            $content .= '  <a  class="btn btn-large btn-info" href="' . $this->naviHref . '?act=free' . $curr_href . '">Free selection(s)</a>';
            $content .= '  <a  class="btn btn-large btn-info" href="' . $this->naviHref . '?act=recall' . $curr_href . '">Un-free selection(s)</a>';
        }
        $content .= '</center>';
        return $content;
    }

    /*     * ******************* PRIVATE ********************* */

    /**
     * create the li element for ul
     */
    private function _showDay($cellNumber) {

        if ($this->currentDay == 0) {

            $firstDayOfTheWeek = date('N', strtotime($this->currentYear . '-' . $this->currentMonth . '-01'));

            if (intval($cellNumber) == intval($firstDayOfTheWeek)) {

                $this->currentDay = 1;
            }
        }

        if (($this->currentDay != 0) && ($this->currentDay <= $this->daysInMonth)) {

            $this->currentDate = date('Y-m-d', strtotime($this->currentYear . '-' . $this->currentMonth . '-' . ($this->currentDay)));

            $cellContent = $this->currentDay . '<br>';

            $this->currentDay++;
        } else {

            $this->currentDate = null;

            $cellContent = null;
        }

        $class = 'unused';
        $help = '';
        if (in_array($this->currentDate, $this->free)) {
            $class = 'free';
            $cellContent .= $this->isUser ? 'available' : 'free';
        } elseif (in_array($this->currentDate, array_keys($this->used))) {
            $class = 'used';
            if ($this->isUser) {
                $owner=$this->used[$this->currentDate];
                $cellContent .= 'reserved<br>' . $owner->baylocation;
                $ph = $this->user_model->find_meta_for($owner->id, array('phone'))->phone;
                $help = $owner->username . ' ' . $ph . ' ' .$owner->email;
            } else {
                $class = 'used';
                $cellContent .= 'used<br>' . $this->used[$this->currentDate];
            }
        }
        
        if (empty($this->currentDate)) {
            $class = 'blank';
        } else if ($this->currentDate == $this->today) {
            $class = 'today';
        } else if ($cellNumber % 7 == 6 || $cellNumber % 7 == 0) {
            $class = 'weekend';
        }
        if ($this->first != null) {
            if ($this->last == null) {
                $this->last = $this->first;
            }
            if ($this->currentDate >= $this->first && $this->currentDate <= $this->last) {
                $class = 'first';
            }
        }


        return '<td  title="'. $help .'" onclick="showMessage(this);" id="' . $this->currentDate . '" class="' . $class . '"' .
                ($cellContent == null ? 'mask' : '') . '">' . $cellContent . '</td>';
    }

    /**
     * create navigation
     */
    private function _createNavi() {

        $nextMonth = $this->currentMonth == 12 ? 1 : intval($this->currentMonth) + 1;

        $nextYear = $this->currentMonth == 12 ? intval($this->currentYear) + 1 : $this->currentYear;

        $preMonth = $this->currentMonth == 1 ? 12 : intval($this->currentMonth) - 1;

        $preYear = $this->currentMonth == 1 ? intval($this->currentYear) - 1 : $this->currentYear;

        return '<th><a class="prev" href="' . $this->naviHref . '?act=move&month=' . sprintf('%02d', $preMonth) . '&year=' . $preYear . '">Prev</a></th>' .
                '<th colspan="5">' . date('Y M', strtotime($this->currentYear . '-' . $this->currentMonth . '-1')) . '</th>' .
                '<th><a class="next" href="' . $this->naviHref . '?act=move&month=' . sprintf("%02d", $nextMonth) . '&year=' . $nextYear . '">Next</a></th>';
    }

    /**
     * create calendar week labels
     */
    private function _createLabels() {

        $content = '';

        foreach ($this->dayLabels as $index => $label) {

            $content .= '<th>' . $label . '</th>';
        }

        return $content;
    }

    /**
     * calculate number of weeks in a particular month
     */
    private function _weeksInMonth($month = null, $year = null) {

        if (null == ($year)) {
            $year = date("Y", time());
        }

        if (null == ($month)) {
            $month = date("m", time());
        }

        // find number of days in this month
        $daysInMonths = $this->_daysInMonth($month, $year);

        $numOfweeks = ($daysInMonths % 7 == 0 ? 0 : 1) + intval($daysInMonths / 7);

        $monthEndingDay = date('N', strtotime($year . '-' . $month . '-' . $daysInMonths));

        $monthStartDay = date('N', strtotime($year . '-' . $month . '-01'));

        if ($monthEndingDay < $monthStartDay) {

            $numOfweeks++;
        }

        return $numOfweeks;
    }

    /**
     * calculate number of days in a particular month
     */
    private function _daysInMonth($month = null, $year = null) {

        if (null == ($year))
            $year = date("Y", time());

        if (null == ($month))
            $month = date("m", time());

        return date('t', strtotime($year . '-' . $month . '-01'));
    }

}
