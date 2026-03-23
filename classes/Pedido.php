<?php

class Pedido {

    private $numero;
    private $cliente;
    private $produtos = [];

    public function __construct($numero, $cliente){
        $this->numero = $numero;
        $this->cliente = $cliente;
    }

    public function adicionarProduto($produto){
        $this->produtos[] = $produto;
    }

    public function calcularTotal(){
        $total = 0;

        foreach($this->produtos as $produto){
            $total += $produto->getPreco();
        }

        return $total;
    }

    public function exibirResumo(){
        echo "Pedido Nº: " . $this->numero . "<br><br>";

        echo "Cliente:<br>";
        echo $this->cliente->getNome() . "<br>";
        echo $this->cliente->getEmail() . "<br><br>";

        echo "Produtos:<br>";

        foreach($this->produtos as $produto){
            echo $produto->getNome() . " - R$ " . $produto->getPreco() . "<br>";
        }

        echo "<br>Total: R$ " . $this->calcularTotal();
    }
}