<?php

namespace Core\Api;

class Response
{
    /**
     * Contains all response data
     * @var array
     */
    private $data = [];

    /**
     * Contains all response errors
     * @var array
     */
    private $errors = [];

    public function __construct($data = [])
    {
        if ($data) {
            $this->setData($data['data'] ?? []);
            $this->setErrors($data['errors'] ?? []);
        }
    }

    /**
     * Prints response
     */
    public function toJson(): string
    {
        $is_success = $this->errors ? false : true;

        return json_encode([
            'status' => $is_success ? 'success' : 'fail',
            'data' => $this->data,
            'errors' => $this->errors
        ]);
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function appendData($body, $index = null)
    {
        if ($index) {
            $this->data[$index] = $body;
        } else {
            $this->data[] = $body;
        }
    }

    public function setErrors($errors)
    {
        $this->errors = $errors;
    }

    public function appendError($body, $index = null)
    {
        if ($index) {
            $this->errors[$index] = $body;
        } else {
            $this->errors[] = $body;
        }
    }

}