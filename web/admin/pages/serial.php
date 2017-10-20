<?php

class Pessoa {
    private $dados = array();

    public function __get($name) {
        if (isset($this->dados[$name])) {
            return $this->dados[$name];
        }
        else {
            throw new Exception( 'Propriedade não existe na classe' );
        }
    }
    
    public function __set($name, $value) {
        $this->dados[$name] = $value;
    }
}


$objeto = new Pessoa();
try {
    $objeto->nome = "Eduardo Monteiro";
    
    print $objeto->nome;
    print $objeto->idade;
}
catch (Exception $ex) {
    print "<p>" . $ex->getMessage() . "</p>";
    $arquivo = file($ex->getFile());
    print "<pre>";
    foreach( range($ex->getLine()-5, $ex->getLine()) as $index ) {
        print $arquivo[$index];
    }
    
    foreach( range($ex->getLine()+1, $ex->getLine()+6) as $index ) {
        print $arquivo[$index];
    }
}
