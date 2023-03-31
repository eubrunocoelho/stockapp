<?php

namespace App\Validator;

class Validator extends DataValidator
{
    private
        $container, $fields, $data, $rules, $errors;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function setData($data)
    {
        $this->data = $data;
    }

    public function setRules($rules)
    {
        $this->rules = $rules;
    }

    public function validation()
    {
        if ($this->fieldsExists()) $this->validate();
        else $this->addError('Houve um erro inesperado.');
    }

    private function fieldsExists()
    {
        foreach ($this->fields as $field)
            if (!array_key_exists($field, $this->data)) return false;

        return true;
    }

    private function validate()
    {
        foreach ($this->rules as $item => $rules)
            foreach ($rules as $rule => $ruleValue) {
                $value = $this->data[$item];
                $label = $rules['label'] ?? $item;

                if ($rule == 'required') {
                    if ($ruleValue && !$this->required($value))
                        $this->addError('O campo "' . $label . '" é obrigatório.');
                }
            }
    }

    private function addError($message)
    {
        $this->errors[] = $message;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function passed()
    {
        return (empty($this->errors)) ? true : false;
    }
}
