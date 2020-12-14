<?php

/**
 * Save PHP array to file in JSON format
 *
 * @param array $array
 * @param string $file_name
 * @return bool
 */
function array_to_file(array $array, string $file_name): bool
{
    $data = json_encode($array);

    $bytes_written = file_put_contents($file_name, $data);

    return $bytes_written !== false;
}

/**
 * Get file and decode it back to PHP array
 *
 * @param string $file_name
 * @return array|bool
 */
function file_to_array(string $file_name)
{
    if (file_exists($file_name)) {
        $data = file_get_contents($file_name);

        if ($data !== false) {
            return json_decode($data, true) ?? [];
        }

        return [];
    }

    return false;
}