<?php

namespace Core\Views;

use Core\View;
use Exception;

class Form extends View
{
    /**
     * Retrieve all form input values
     *
     * @return array|null
     */
    public function values(): ?array
    {
        $parameters = [];

        foreach ($this->data['fields'] as $key => $input) {
            $parameters[$key] = FILTER_SANITIZE_SPECIAL_CHARS;
        }

        return filter_input_array(INPUT_POST, $parameters);
    }

    /**
     * Retrieve single input value
     *
     * @param $field_id
     * @return mixed
     */
    public function value($field_id)
    {
        return filter_input(INPUT_POST, $field_id, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    /**
     * Checks if form was submitted
     *
     * Form counts as submitted if pressed button index exists
     * within form buttons. If two forms are used,
     * be careful not to use same indexes for buttons
     *
     * @return bool
     */
    public function isSubmitted(): bool
    {
        return isset($this->data['buttons'][self::action()]);
    }

    /**
     * Validates all form fields
     *
     * @return bool
     */
    public function validate(): bool
    {
        if ($this->isSubmitted()) {
            $is_valid = true;
            $form_values = $this->values();

            // Runs all field-level validators
            foreach ($this->data['fields'] as $field_id => &$field) {
                foreach ($field['validators'] ?? [] as $function_index => $function_name) {
                    $field_value = $form_values[$field_id];

                    if (is_array($function_name)) {
                        $params = $function_name;
                        $field_is_valid = $function_index($field_value, $field, $params);
                    } else {
                        $field_is_valid = $function_name($field_value, $field);
                    }

                    if (!$field_is_valid) {
                        $is_valid = false;
                        break;
                    } else {
                        // Sets field value if was filled correctly
                        // so user doesnt have to re-enter
                        $field['value'] = $field_value;
                    }
                }
            }

            // Runs all form-level validators
            foreach ($this->data['validators'] ?? [] as $validator_name => $validator) {
                if (is_array($validator)) {
                    $field_is_valid = $validator_name($form_values, $this->data, $params = $validator);
                } else {
                    $field_is_valid = $validator($form_values, $this->data);
                }

                if (!$field_is_valid) {
                    $is_valid = false;
                    break;
                }
            }

            return $is_valid;
        }

        return false;
    }

    /**
     * Get current form errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        $errors = [];

        foreach ($this->data['fields'] as $field_id => $field) {
            if (isset($field['error'])) {
                $errors[$field_id] = $field['error'];
            }
        }

        return $errors;
    }

    /**
     * Fills form inputs with given values
     *
     * @param array $values
     * @throws Exception
     */
    public function fill(array $values): void
    {
        foreach ($values as $value_id => $value) {
            if (isset($this->data['fields'][$value_id])) {
                $this->data['fields'][$value_id]['value'] = $value;
            } else {
                throw new \RuntimeException("{$value_id} field doesnt exist");
            }
        }
    }

    /**
     * Retrieves the ID of which button was pressed
     *
     * @return mixed
     */
    public static function action(): ?string
    {
        return filter_input(INPUT_POST, 'action', FILTER_SANITIZE_SPECIAL_CHARS);
    }

    /**
     * Renders form as HTML
     *
     * @param string $template_path
     * @return false|string
     * @throws Exception
     */
    public function render($template_path = ROOT . '/core/templates/form.tpl.php'): string
    {
        return parent::render($template_path);
    }
}