<?php

namespace App\Model;

class Gestor
{
    private
        $ID, $usuario, $nome, $email, $cpf, $senha, $telefone, $endereco, $cargo, $genero, $status, $img_url;


    public function setID($ID)
    {
        $this->ID = $ID;
    }

    public function getID()
    {
        return $this->ID;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }

    public function getCpf()
    {
        return $this->cpf;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    public function getSenha()
    {
        return $this->senha;
    }

    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    public function getTelefone()
    {
        return $this->telefone;
    }

    public function setEndereco($endereco)
    {
        $this->endereco = $endereco;
    }

    public function getEndereco()
    {
        return $this->endereco;
    }

    public function setCargo($cargo)
    {
        $this->cargo = $cargo;
    }

    public function getCargo()
    {
        return $this->cargo;
    }

    public function setGenero($genero)
    {
        $this->genero = $genero;
    }

    public function getGenero()
    {
        return $this->genero;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setImgUrl($img_url)
    {
        $this->img_url = $img_url;
    }

    public function getImgUrl()
    {
        return $this->img_url;
    }
}
