<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');




function date_to_timestamp( $date_str ){ //принимает строку вида dd.mm.yyyy
    
    $date_ar = explode('.', trim($date_str) );
    if( count($date_ar) < 3 ) 
        return '0000-00-00 00:00:00';
    else
        return $date_ar[2].'-'.$date_ar[1].'-'.$date_ar[0].' 00:00:00';
}


function timestamp_to_date( $timestamp, $short = false ){
    $date_time_ar   = explode(' ', $timestamp); 
    $date_ar        = explode('-', $date_time_ar[0]);
    if( $short )
        return $date_ar[2].'.'.$date_ar[1];
    else
        return $date_ar[2].'.'.$date_ar[1].'.'.$date_ar[0];
}

function date_to_time( $date_str ){
    $date_ar = explode('.', trim($date_str) );
    if( count($date_ar) < 3 ) 
        return 0;
    else
        return mktime (0, 0, 0, $date_ar[1], $date_ar[0], $date_ar[2]);
}

function timestamp_to_time( $timestamp ){
    return date_to_time( timestamp_to_date($timestamp) );
}

function summ_time( $time_1, $operation, $time_2 ){ // $time = '00:00:00' $operation = '-'/'+'
    $time_2_ar = explode(':', $time_2 );
    
    $result_time = date("H:i:s", strtotime("$operation $time_2_ar[0] hour, $operation $time_2_ar[1] minute", strtotime($time_1) ) );
    
    return $result_time;
}

function get_month_or_week_period( $period = FALSE, $date = FALSE ){
    
    if( $period == 'week'){
        
        $day_nbr = date("w", strtotime($date) );
        if( $day_nbr == 0 ) $day_nbr = 7; //воскресение
        if( $day_nbr != 1 ){ // если не понедельник, то высчитывается дата понедельника
            $cnt_day_from_mondey = $day_nbr - 1; 
            $mondey_date    = date("Y-m-d", strtotime("-{$cnt_day_from_mondey} day", strtotime($date) ) );
        }
        else
            $mondey_date    = date("Y-m-d", strtotime($date)  );
        
        $next_mondey_date   = date("Y-m-d", strtotime("+1 week", strtotime($mondey_date) ) );
        
        $result_ar['start']     = $mondey_date;
        $result_ar['stop']      = $next_mondey_date;
        $result_ar['last_day']  = date("Y-m-d", strtotime("-1 day", strtotime($next_mondey_date) ) );
        
    }
    elseif( $period == 'month' ){
        
        $day_nbr = date("j", strtotime($date) );
        if( $day_nbr != 1 ){ // если не первое число, то высчитывается дата первого
            $cnt_day_from_mondey = $day_nbr - 1; 
            $firstday_date  = date("Y-m-d", strtotime("-{$cnt_day_from_mondey} day", strtotime($date) ) );
        }
        else
            $firstday_date  = date("Y-m-d", strtotime($date)  );
        
        $next_monthday_date = date("Y-m-d", strtotime("+1 month", strtotime($firstday_date) ) );
        
        $result_ar['start'] = $firstday_date;
        $result_ar['stop']  = $next_monthday_date;
    }
    
    return $result_ar;
}

function get_next_prev_date_for_payment( $period, $date_ar ){
    if($period == 'week'){
        $return_ar['next'] = $date_ar['stop'];
        $return_ar['prev'] = date("Y-m-d", strtotime("-1 week", strtotime($date_ar['start']) ) );
    }
    elseif($period == 'month'){
        $return_ar['next'] = $date_ar['stop'];
        $return_ar['prev'] = date("Y-m-d", strtotime("-1 month", strtotime($date_ar['start']) ) );
    }
    else
        $return_ar = NULL;
    
    return $return_ar;
}

function get_date_str_ar( $date = '2012-05-07' ){
//    $day_ar     = array('Воскресенье','Понедельник','Вторник','Среда','Четверг','Пятница','Суббота');
//    $month_ar   = array( 1=>'января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
//    
//    $day_ar     = array('Dimanche','Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi');
//    $month_ar   = array( 1=>'janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre');
    
    $ci = &get_instance();
    
    $day_ar     = $ci->multidomaine['day_ar'];
    $month_ar   = $ci->multidomaine['month_ar'];
    
    $dateInt = strtotime($date);
    
    $result_ar['day_str']           = $day_ar[date("w", $dateInt )];
    $result_ar['day_nmbr']          = date("d", $dateInt );
    $result_ar['month_str']         = $month_ar[date("n", $dateInt )];
    $result_ar['month_nmbr']        = date("m", $dateInt );
    $result_ar['year_nmbr']         = date("Y", $dateInt );
    $result_ar['year_short_nmbr']   = date("y", $dateInt );
    $result_ar['time']              = date("H:i", $dateInt );
    
    return $result_ar;
}

function get_timesize( $start_less, $stop_less){ //определяет размер блока в расписании

    $time_result = summ_time($stop_less, '-', $start_less); //получение разницы времени
    
    $time_ar = explode(':', $time_result );
    
    $time_period = (int) ltrim($time_ar[0], 0) + ( (int) trim( str_replace('3', '5', $time_ar[1]),0) / 10); //перевод периода в число
    
    $timesize =  $time_period * 2;
            
    return $timesize;
}

function get_timestop($time, $timesize){ //получает время начала занятий и продолжительность, возврощает время окончания занятий
    $plus_time = $timesize/2;
    $plus_h = floor($plus_time);
    
    if( $plus_h != $plus_time)
        $plus_min_str = "30 minute";
    else
        $plus_min_str = '';
    
    $result_time = date("H:i:00", strtotime("+ $plus_h hour $plus_min_str", strtotime($time) ) );
    
    return $result_time;
}

function get_week_day_ar($d, $m, $y){ //принимает день, месяц, год. Возвращает массив из 7 последующих дат
    $date_ar = array();
    for($i=0; $i<7; $i++){
        $date_ar[$i+1] = date("Y-m-d", strtotime("+ $i day", strtotime("$y-$m-$d") ) );
    }
    
    return $date_ar;
}

//function get_count_day($date_start, $date_stop){//принимает начальную и конечную дату, возвращает количество дней между ними. формат даты YYYY-MM-DD
//    $time_1 = timestamp_to_time($date_start);
//    $time_2 = timestamp_to_time($date_stop);
//    
//    return ($time_2 - $time_1)/3600/24;
//}