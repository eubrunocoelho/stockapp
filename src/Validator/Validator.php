<?php

namespace App\Validator;

class Validator extends Validators
{
    private
        $container, $fields, $data, $rules, $errors;

    public function __construct($container)
    {
        $this->container = $container;

        parent::__construct($this->container);
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
                    if ($ruleValue && !parent::required($value))
                        $this->addError('O campo "' . $label . '" é obrigatório.');
                } elseif (parent::required($value)) switch ($rule) {
                    case 'unique-update':
                        if (!parent::uniqueForUpdate($value, $ruleValue))
                            $this->addError('O "' . $label . '" já está cadastrado no banco de dados.');
                        break;
                    case 'unique':
                        if (!parent::unique($value, $ruleValue))
                            $this->addError('O "' . $label . '" já está cadastrado no banco de dados.');
                        break;
                    case 'min':
                        if (!parent::min($value, $ruleValue))
                            $this->addError('O campo "' . $label . '" deve conter no mínimo ' . $ruleValue . ' caracteres.');
                        break;
                    case 'max':
                        if (!parent::max($value, $ruleValue))
                            $this->addError('O campo "' . $label . '" deve conter no máximo ' . $ruleValue . ' caracteres.');
                        break;
                    case 'regex':
                        if (!parent::regex($value, $ruleValue))
                            $this->addError('O campo "' . $label . '" está inválido.');
                        break;
                    case 'email':
                        if (!parent::email($value))
                            $this->addError('O campo "' . $label .  '" está inválido.');
                        break;
                    case 'cpf':
                        if (!parent::cpf($value))
                            $this->addError('O campo "' . $label . '" está inválido.');
                        break;
                }
            }
    }
    
    public function addError($message)
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
