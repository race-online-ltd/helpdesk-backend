<?php

namespace App\Helpers;

class Calculation
{
    public static function convertToMinutes($days, $hours, $minutes)
    {
        $totalDays = is_numeric($days) ? (int)$days : 0;
        $totalHours = is_numeric($hours) ? (int)$hours : 0;
        $totalMinutes = is_numeric($minutes) ? (int)$minutes : 0;

        return ($totalDays * 1440) + ($totalHours * 60) + $totalMinutes;
    }

    public static function convertToString($days, $hours, $minutes)
    {
        $totalDays = is_numeric($days) ? (int)$days : 0;
        $totalHours = is_numeric($hours) ? (int)$hours : 0;
        $totalMinutes = is_numeric($minutes) ? (int)$minutes : 0;

        return "{$totalDays}d {$totalHours}h {$totalMinutes}m";
    }


    public static function getFirstResponseTriggerTime($notifyTime, $slatime)
    {
        switch ($notifyTime) {
            case "Immediately":
                return $slatime;

            case "After 30 minutes":
                preg_match("/(\d+)\s*minutes?/i", $notifyTime, $matches);
                return (int) $matches[1] + $slatime;

            case "After 1 hour":
            case "After 2 hour":
            case "After 3 hour":
                preg_match("/(\d+)\s*hours?/i", $notifyTime, $matches);
                return ((int) $matches[1] * 60) + $slatime;

            default:
                return 0;
        }
    }
}
