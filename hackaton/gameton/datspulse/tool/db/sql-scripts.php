<?php
require_once "../../src/setting.php";
require_once "../../src/db.php";
require_once "../../src/time.php";

$requestUID = uniqid(time(), true);
$startTime = microtime(true);

$scriptList = [
    0 => "../../db/main-clean.sql",
    1 => '../../db/main-schema.sql',
];

try {
    $db = DB::GetConnection();

    foreach ($scriptList as $script) {
        $query = file_get_contents($script);
        if ($query === false || empty($query)) {
            echo "skip process '{$script}'" . PHP_EOL;
            continue;
        }
        echo "processing '{$script}'";
        $db->exec($query);
        echo " - done" . PHP_EOL;
    }
} catch (\Throwable $e) {
    echo PHP_EOL . 'Error: ' . $e->getMessage() . PHP_EOL;
} finally {
    $res = calc_execution_time($startTime);
    echo 'Done: ' . date('Y-m-d H:i') . PHP_EOL;
    echo 'Execution Time: ' . $res['execution'] . PHP_EOL;
}
