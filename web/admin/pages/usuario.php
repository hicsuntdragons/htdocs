<?php

class Usuario {
    private $id = null;
    private $nome = null;
    private $email = null;
    private $senha = null;
    private $area = null;
    private $tipo = null;
    
    public function getId() {
        return $this->id;
    }
	
    public function getTipo() {
        return $this->tipo;
    }
    
    public function getNome() {
        return $this->nome;
    }
    
    public function getEmail() {
        return $this->email;
    }
	
    public function getArea() {
        return $this->area;
    }
    
    public function getSenha() {
        return $this->senha;
    }
    
    public function setId($id) {
        $this->id = $id;
    }
	
    public function setTipo($tipo) {
        $this->tipo = $tipo;
    }
	
    public function setArea($area) {
        $this->area = $area;
    }
    
    public function setNome($nome) {
        $this->nome = $nome;
    }
    
    public function setEmail($email) {
        $this->email = $email;
    }
    
    public function setSenha($senha) {
        $this->senha = $senha;
    }
}

