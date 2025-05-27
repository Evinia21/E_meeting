<?php
function formatDateTime($datetime) {
    return date("d F Y, H:i:s", strtotime($datetime));
}
?>
