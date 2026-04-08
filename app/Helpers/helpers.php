<?php

if (!function_exists('formatTime')) {
    function formatTime($seconds) {
        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        return sprintf("%dd %dh %dm", $days, $hours, $minutes);
    }
}
