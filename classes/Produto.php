<?php

class Produto {

    private $id;
    private $nome;
    private $preco;

    public function __construct($id, $nome, $preco){
        $this->id = $id;
        $this->nome = $nome;
        $this->setPreco($preco);
    }

    public function getId(){
        return $this->id;
    }

    public function getNome(){
        return $this->nome;
    }

    public function setNome($nome){
        $this->nome = $nome;
    }

    public function getPreco(){
        return $this->preco;
    }

    public function setPreco($preco){
        if($preco < 0){
            echo "Erro: preço não pode ser negativo!<br>";
            return;
        }

        $this->preco = $preco;
    }
}