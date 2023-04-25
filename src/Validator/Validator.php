<?php

namespace App\Validator;

use App\{
    Helper\Input
};

/**
 * Responsável por fazer validações do formulário
 */
class Validator extends Validators
{
    private
        $container, $fields, $data, $rules, $errors;

    public function __construct($container)
    {
        $this->container = $container;

        parent::__construct($this->container);
    }

    /**
     * Define os campos que devem existir no formulário
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * Define os dados recebidos do formulário
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * Define as regras do formulário
     */
    public function setRules($rules)
    {
        $this->rules = $rules;
    }

    /**
     * Responsável por iniciar a validação
     */
    public function validation()
    {
        if ($this->fieldsExists()) $this->validate();
        else $this->addError('Houve um erro inesperado.');
    }

    /**
     * Verifica se os campos do formulário condizem com os dados recebidos no formulário
     */
    private function fieldsExists()
    {
        foreach ($this->fields as $field)
            if (!array_key_exists($field, $this->data)) return false;

        return true;
    }

    /**
     * Responsável por fazer a validação e gerar erros
     */
    private function validate()
    {
        // Percorre as regras e faz a validação do formulário
        foreach ($this->rules as $item => $rules)
            foreach ($rules as $rule => $ruleValue) {
                // Define o valor recebido do formulário
                $value = $this->data[$item];
                // Define o nome `label` do item a ser validado
                $label = $rules['label'] ?? $item;

                // Aplica as regras
                if ($rule == 'required') {
                    if ($ruleValue && !parent::required($value))
                        $this->addError('O campo "' . $label . '" é obrigatório.');
                } elseif (parent::required($value)) switch ($rule) {
                    case 'unique-update':
                        if (!parent::uniqueForUpdate(Input::numeric($value), $ruleValue))
                            $this->addError('O "' . $label . '" já está cadastrado no banco de dados.');
                        break;
                    case 'unique':
                        if (!parent::unique(Input::numeric($value), $ruleValue))
                            $this->addError('O "' . $label . '" já está cadastrado no banco de dados.');
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
                        if (!parent::cpf(Input::numeric($value)))
                            $this->addError('O campo "' . $label . '" esta inválido.');
                        break;
                    case 'telephone':
                        if (!parent::telephone(Input::numeric($value)))
                            $this->addError('O campo "' . $label . '" está inválido.');
                        break;
                }
            }
    }

    /**
     * Adiciona errors que ocorreram durante a validação
     */
    private function addError($message)
    {
        $this->errors[] = $message;
    }

    /**
     * Obtem erros que ocorreram durante a validação
     */
    public function errors()
    {
        return $this->errors;
    }

    /**
     * Verifica se validação foi concluída sem erros
     */
    public function passed()
    {
        return (empty($this->errors)) ? true : false;
    }
}
