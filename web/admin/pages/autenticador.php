<?php

abstract class Autenticador {
    
    private static $instancia = null;
    
    private function __construct() {}
    
    /**
     * 
     * @return Autenticador
     */
    public static function instanciar() {
        
        if (self::$instancia == NULL) {
            self::$instancia = new AutenticadorEmBanco();
        }
        
        return self::$instancia;
        
    }
    
    public abstract function logar($email, $senha);
    public abstract function esta_logado();
    public abstract function pegar_usuario();
    public abstract function expulsar();
    
}

class AutenticadorEmMemoria extends Autenticador {
    
    public function esta_logado() {
        $sess = Sessao::instanciar();
        return $sess->existe('usuario');
    }
    
    public function logar($email, $senha) {
        $sess = Sessao::instanciar();
                
        if ($email == 'email@gmail.com' && $senha == 'senha') {
            $usuario = new Usuario();
            $usuario->setEmail($email);
            $usuario->setId(1);
            $usuario->setNome('Nome do usuário');
            $usuario->setSenha($senha);
            
            $sess->set('usuario', $usuario);
            return true;
        }
        else {
            return false;
        }
        
    }
    
    public function pegar_usuario() {
        $sess = Sessao::instanciar();
        
        if ($this->esta_logado()) {
            $usuario = $sess->get('usuario');
            return $usuario;
        }
        else {
            return false;
        }
        
    }
    
    public function expulsar() {
        header('location: controle.php?acao=sair');
    }
    
}

class AutenticadorEmBanco extends Autenticador {
    
    public function esta_logado() {
        $sess = Sessao::instanciar();
        return $sess->existe('usuario');
    }

    public function expulsar() {
        header('location: controle.php?acao=sair');
    }

    public function logar($email, $senha) {
        
		$pdo = new PDO('mysql:host=u426573602-ene.mysql.uhserver.com;dbname=u426573602_ene', 'u426573602_root','@p0sitiv02014')or print (mysql_error());
		//$pdo = new PDO('mysql:host=mysql.hostinger.com.br;dbname=u605743479_aval', 'u605743479_root','p0sitiv02014')or print (mysql_error());
		//$pdo = new PDO('mysql:host=localhost;dbname=enepet2017', 'root','')or print (mysql_error());
		
		$a = $pdo->prepare("SET NAMES 'utf8'"); 
		$a->execute();
		$b = $pdo->prepare('SET character_set_connection=utf8'); 
		$b->execute();
		$c = $pdo->prepare('SET character_set_client=utf8'); 
		$c->execute();
		$d = $pdo->prepare('SET character_set_results=utf8'); 
		$d->execute();
		 
        $sess = Sessao::instanciar();
        
        $sql = "select * 
               from usuarios 
               where usuarios.email ='{$email}' and
                   usuarios.senha = '{$senha}'";
                   
        $stm = $pdo->query($sql);
        
        if ($stm->rowCount() > 0) {
        
            $dados = $stm->fetch(PDO::FETCH_ASSOC);

            $usuario = new Usuario();
            $usuario->setEmail($dados['email']);
            $usuario->setId($dados['id']);
            $usuario->setTipo($dados['tipo']);
            $usuario->setArea($dados['area']);
            $usuario->setNome($dados['nome']);
            $usuario->setSenha($dados['senha']);

            $sess->set('usuario', $usuario);
            return true;
            
        }
        else {
            return false;
        }
        
    }

    public function pegar_usuario() {
        $sess = Sessao::instanciar();
        
        if ($this->esta_logado()) {
            $usuario = $sess->get('usuario');
            return $usuario;
        }
        else {
            return false;
        }
    }

}