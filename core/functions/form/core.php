<?php

/**
 * Get form inputs and filter them (e.g. sanitize special characters).
 *
 * @param array $form
 * @return array|null
 */
function get_clean_input(array $form): ?array
{
    $parameters = [];

    foreach ($form['fields'] as $index => $input) {
        $parameters[$index] = FILTER_SANITIZE_SPECIAL_CHARS;
    }

    return filter_input_array(INPUT_POST, $parameters);
}

/**
 * Helper function to run validation functions of forms and fields
 *
 * @param array $form
 * @param array $form_values
 * @return bool
 */
function validate_form(array &$form, array $form_values): bool
{
    $is_valid = true;

    foreach ($form['fields'] as $field_index => &$field) {
        foreach ($field['validators'] ?? [] as $validator_index => $validator) {
            if (is_array($validator)) {
                $field_is_valid = $validator_index($form_values[$field_index], $field, $params = $validator);
            } else {
                $field_is_valid = $validator($form_values[$field_index], $field);
            }
            if (!$field_is_valid) {
                $is_valid = false;
                break;
            }
        }
    }

    if (isset($form['validators'])) {
        foreach ($form['validators'] as $validator_index => $validator) {
            if (is_array($validator)) {
                $field_is_valid = $validator_index($form_values, $form, $params = $validator);
            } else {
                $field_is_valid = $validator($form_values, $form);
            }

            if (!$field_is_valid) {
                $is_valid = false;
                break;
            }
        }
    }

    return $is_valid;
}
