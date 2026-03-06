<?php

if (!function_exists('formatIndonesianRelativeTime')) {
    /**
     * Format a date into an Indonesian relative time string.
     * 
     * Handles:
     * - "Baru saja" (less than 1 min)
     * - "X menit yang lalu" (less than 1 hour)
     * - "X jam yang lalu" (less than 24 hours AND same day)
     * - "Kemarin, HH:mm" (previous day, regardless of 24h window)
     * - "X hari yang lalu" (older than yesterday)
     * 
     * @param mixed $date string, integer, DateTime, or Carbon instance
     * @return string
     */
    function formatIndonesianRelativeTime($date)
    {
        if (empty($date))
            return '-';

        $carbonDate = \Carbon\Carbon::parse($date)->locale('id');
        $now = \Carbon\Carbon::now()->locale('id');

        $diffInSeconds = $now->diffInSeconds($carbonDate);
        $diffInMinutes = $now->diffInMinutes($carbonDate);
        $diffInHours = $now->diffInHours($carbonDate);

        // Check if it's today
        $isToday = $carbonDate->isToday();

        // Check if it's yesterday
        $isYesterday = $carbonDate->isYesterday();

        // 1. Baru saja (less than 60 seconds)
        if ($diffInSeconds < 60) {
            return 'Baru saja';
        }

        // 2. X menit yang lalu (less than 60 minutes)
        if ($diffInMinutes < 60) {
            return $diffInMinutes . ' menit yang lalu';
        }

        // 3. X jam yang lalu (If it's STILL TODAY)
        if ($isToday) {
            return $diffInHours . ' jam yang lalu';
        }

        // 4. Kemarin (If it's YESTERDAY, even if less than 24 hours ago)
        if ($isYesterday) {
            return 'Kemarin, ' . $carbonDate->format('H:i');
        }

        // 5. X hari yang lalu (More than 1 day ago)
        $diffInDays = $now->startOfDay()->diffInDays($carbonDate->copy()->startOfDay());
        return $diffInDays . ' hari yang lalu';
    }
}
