<?php

require_once "./time.php";

function save_array_as_csv($path, $data)
{
    $data_csv = implode(';', $data);
    $cvs_file = "csv_" . get_current_date_time_as_sting() . '.cvs';
    $cvs_path = "{$path}/{$cvs_file}";
    if (file_put_contents($cvs_path, $data_csv) === FALSE) {
        throw new Exception("Не могу записать данные в файл: {$cvs_file}");
    }
    return $cvs_path;
}
