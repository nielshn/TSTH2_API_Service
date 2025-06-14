<?php

if (!function_exists('generatePermissionsFlags')) {
    function generatePermissionsFlags(array $permissions, array $keys): array
    {
        $flags = [];

        foreach ($keys as $key) {
            $flags['can_' . $key] = in_array($key, $permissions);
        }

        return $flags;
    }
}
