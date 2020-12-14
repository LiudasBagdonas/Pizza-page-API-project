<?php

/**
 * Iš duoto duomenų masyvo sukuria atributus
 * deklaruojantį tekstą HTML elementui. (pavadinimas="vertė")
 *
 * @param array $attr masyvas HTML atributų porų.
 * @return string HTML atributai.
 */
function html_attr(array $attr): string
{
    $attribute_string = '';

    foreach ($attr as $name => $value) {
        $attribute_string .= "$name=\"$value\" ";
    }

    return $attribute_string;
}

/**
 * Funkcija kuri išspausdina formos tago atributus
 *
 * @param $form
 * @return string
 */
function form_attr($form)
{
    return html_attr($form['attr'] ?? [] + [
            'method' => 'POST'
        ]);
}

/**
 * Iš duoto duomenų masyvo sukuria atributus
 * deklaruojantį tekstą skirtą HTML input elementui.
 *
 * Sumuojami atributai yra name, type, value ir visi likę
 * atributai iš $field['extra']['attr'] masyvo.
 *
 * @param string $field_name HTML input'o pavadinimas.
 * @param array $field masyvas HTML input atributų.
 * @return string input elemento atributai.
 */
function input_attr(string $field_name, array $field): string
{
    $attributes = [
            'name' => $field_name,
            'type' => $field['type'],
            'value' => $field['value'] ?? '',
        ] + ($field['extra']['attr'] ?? []);

    return html_attr($attributes);
}

/**
 * Iš duoto duomenų masyvo sukuria atributus
 * deklaruojantį tekstą HTML button elementui.
 *
 * @param string $button_id HTML button'o value atributas.
 * @param array $button masyvas HTML button atributų.
 * @return string input elemento atributai.
 */
function button_attr(string $button_id, array $button): string
{
    $attributes = [
            'name' => 'action',
            'type' => $button['type'] ?? 'submit',
            'value' => $button_id,
        ] + ($button['extra']['attr'] ?? []);

    return html_attr($attributes);
}

function select_attr(string $field_id, array $field): string
{
    $attributes = [
            'name' => $field_id,
            'value' => $field['value'],
        ] + ($field['extra']['attr'] ?? []);

    return html_attr($attributes);
}

function option_attr(string $option_id, array $option): string
{
    $attributes = [
        'value' => $option_id,
    ];

    if ($option['value'] === $option_id) {
        $attributes['selected'] = 'selected';
    }

    return html_attr($attributes);
}

/**
 * Generate <textarea> HTML element from an array
 *
 * @param string $textarea_id
 * @param array $textarea
 * @return string
 */
function textarea_attr(string $textarea_id, array $textarea): string
{
    $attributes = [
            'name' => $textarea_id,
            'rows' => $textarea['rows'] ?? 5, // could be specified in CSS
            'cols' => $textarea['cols'] ?? 20, // could be specified in CSS
        ] + ($textarea['extra']['attr'] ?? []);

    return html_attr($attributes);
}