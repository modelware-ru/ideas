<?php

function calc_execution_time($startTime)
{
    $finishTime = microtime(true);
    return [
        'start' => date('Y-m-d H:i:s', intval($startTime)),
        'finish' => date('Y-m-d H:i:s', intval($finishTime)),
        'execution' => sprintf('%f', $finishTime - $startTime),
    ];
}

function get_current_date_time_as_sting() {
    return date('Y_m_d__H_i_s_u');
}