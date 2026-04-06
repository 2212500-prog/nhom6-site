<?php
header('Content-Type: application/json; charset=UTF-8');
header('Cache-Control: no-cache');
$ctx = stream_context_create(['http' => [
    'header'  => 'Authorization: Basic ' . base64_encode('admin:123456'),
    'timeout' => 2
]]);
$csv = @file_get_contents('http://192.168.150.10:8404/stats;csv;norefresh', false, $ctx);
if (!$csv) { echo '{}'; exit; }
$lines   = explode("\n", trim($csv));
$headers = str_getcsv(ltrim($lines[0], '# '));
$result  = [];
foreach (array_slice($lines, 1) as $line) {
    if (!$line) continue;
    $row = array_combine($headers, str_getcsv($line));
    if (in_array($row['svname'], ['web1','web2'])) {
        $result[$row['svname']] = [
            'status'   => $row['status'],
            'requests' => number_format((int)$row['stot']),
            'cur_conn' => $row['scur'],
            'bytes_in' => round((int)$row['bin'] / 1024, 1),
        ];
    }
}
echo json_encode($result);
