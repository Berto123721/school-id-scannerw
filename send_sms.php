<?php
$data = json_decode(file_get_contents('php://input'), true);
$student_id = $data['student_id'] ?? '';

if (!$student_id) {
  echo "No student ID provided.";
  exit;
}

// Load "database"
$students = json_decode(file_get_contents("student_data.json"), true);

// Lookup student
if (!isset($students[$student_id])) {
  echo "Student not found.";
  exit;
}

$student = $students[$student_id];
$phone_number = $student["parent_number"];
$name = $student["name"];

// Message
$message = "Na-scan na ang ID ni $name ($student_id). Salamat!";

// SEMAPHORE SMS API
$api_key = "YOUR_SEMAPHORE_API_KEY"; // <-- ilisi ug imong API key

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.semaphore.co/api/v4/messages');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    'apikey' => $api_key,
    'number' => $phone_number,
    'message' => $message,
    'sendername' => 'SchoolID'
]));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

echo "SMS sent to $phone_number";
?>
