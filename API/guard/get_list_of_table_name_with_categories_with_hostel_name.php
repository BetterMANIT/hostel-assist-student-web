<?php
require_once '../db_connect.php';
include '../../debug_config.php'; 
header('Content-Type: application/json');

function parse_variable_table_name($variable_table_name) {
    $today = date("d");
    $month = date("m");
    $year = date("Y");
    $twoDigitYear = date("y");

    $variable_table_name = str_replace("{dd}", $today, $variable_table_name);
    $variable_table_name = str_replace("{mm}", $month, $variable_table_name);
    $variable_table_name = str_replace("{yyyy}", $year, $variable_table_name);
    $variable_table_name = str_replace("{yy}", $twoDigitYear, $variable_table_name);

    return $variable_table_name;
}

function fetchHostelCategories($db_conn) {
    $query = "SELECT hostel_name, category_name, variable_table_name_suffix FROM hostel_with_categories";
    $result = $db_conn->query($query);

    if (!$result) {
        return [
            'status' => 'error',
            'message' => 'Query failed: ' . $db_conn->error
        ];
    }

    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $parsed_variable_table_name = parse_variable_table_name($row['variable_table_name_suffix']);
        $table_name = $row['hostel_name'] . $parsed_variable_table_name;

        if (!isset($categories[$row['hostel_name']])) {
            $categories[$row['hostel_name']] = [];
        }
        $categories[$row['hostel_name']][] = [
            'table_name' => $table_name,
            'category_name' => $row['category_name']
        ];
    }

    return [
        'status' => 'success',
        'data' => $categories
    ];
}

$response = fetchHostelCategories($db_conn);
echo json_encode($response);
$db_conn->close();
?>
