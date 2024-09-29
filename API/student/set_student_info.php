<?php 
include '../../debug_config.php';
include 'db_connect.php';

header('Content-Type: application/json');

if (!isset($_GET['scholar_no'])) {
    echo json_encode(['status' => 'error', "message" => "Error: scholar_no is a mandatory parameter."]);
    exit;
}

$scholar_no = $_GET['scholar_no'];

$fields = [];
$values = [];

foreach ($_GET as $key => $value) {
    if ($key !== 'scholar_no' && !empty($value)) {
        $fields[] = "$key = ?";
        $values[] = $value;
    }
}

$response = ['status' => 'success'];

if (count($fields) > 0) {
    $sql = "UPDATE student_info 
            SET " . implode(", ", $fields) . " 
            WHERE scholar_no = ?";

    $stmt = $db_conn->prepare($sql);
    
    if (!$stmt) {
        echo json_encode(["error" => "Prepare failed: " . $db_conn->error]);
        exit; 
    }

    $types = '';
    foreach ($values as $value) {
        if (is_int($value)) {
            $types .= 'i';
        } elseif (is_double($value)) {
            $types .= 'd'; 
        } else {
            $types .= 's'; 
        }
    }

    $types .= 's';
    $values[] = $scholar_no;

    // Bind parameters
    $stmt->bind_param($types, ...$values);

    // Execute the statement
    if ($stmt->execute()) {
        $response["message"] = "Record updated successfully.";
    } else {
        $response["status"] = "error";
        $response["message"] = "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    $response["status"] = "error";
    $response["message"] = "No optional parameters provided. No updates were made.";
}

$db_conn->close();


echo json_encode($response);
?>
