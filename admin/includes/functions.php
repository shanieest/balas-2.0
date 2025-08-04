<?php
function logActivity($conn, $userId, $activity) {
    $stmt = $conn->prepare("INSERT INTO activity_logs (user_id, activity, timestamp) VALUES (?, ?, NOW())");
    $stmt->bind_param("is", $userId, $activity);
    $stmt->execute();
    $stmt->close();
}
?>
