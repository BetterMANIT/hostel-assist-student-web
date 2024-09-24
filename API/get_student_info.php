<?php
    include 'db_connect.php';

    header('Content-Type: application/json'); // Set content type to JSON

    if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET') {
        // Get scholar_no from POST or GET request
        $scholar_no = ($_SERVER['REQUEST_METHOD'] == 'POST') ? $_POST['scholar_no'] : $_GET['scholar_no'];

        // Prepared statement to fetch student details based on scholar_no
        $stmt = $db_conn->prepare("SELECT * FROM student_info WHERE scholar_no = ?");
        $stmt->bind_param("s", $scholar_no);  // 's' denotes the type (string)

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Fetch the student's details
            $row = $result->fetch_assoc();
            
            // Create an associative array with the fetched data
            $student_data = [
                'scholar_no' => $row['scholar_no'],
                'name' => $row['name'],
                'room_no' => $row['room_no'],
                'photo_url' => $row['photo_url'],
                'phone_no' => $row['phone_no'],
                'section' => $row['section'],
                'guardian_no' => $row['guardian_no']
            ];

            // Send data as JSON response
            echo json_encode(['status' => 'success', 'data' => $student_data]);
        } else {
            // If no student is found, return an error message
            echo json_encode(['status' => 'error', 'message' => 'No student found with Scholar No.: ' . $scholar_no]);
        }

        // Closing the statement and connection
        $stmt->close();
        $db_conn->close();
    } else {
        // If the request method is not POST or GET, return an error
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method. Only POST and GET are accepted.']);
    }
?>
