<?php
function showToast(string $message, string $type = 'info', int $duration = 5000): void
{
    $allowed = ['success', 'error', 'warning', 'info'];
    if (!in_array($type, $allowed)) $type = 'info';
    $duration = (int) $duration;

    // safe JS encoding for the message
    $messageJs = json_encode($message, JSON_UNESCAPED_UNICODE);

    // include external CSS/JS (paths are relative to the served page)
    echo '<link rel="stylesheet" href="components/sonner.css">';
    echo '<script src="components/sonner.js"></script>';

    echo "<script>if(window.sonnerShowToast) sonnerShowToast({$messageJs},'{$type}',{$duration});</script>";
}
?>