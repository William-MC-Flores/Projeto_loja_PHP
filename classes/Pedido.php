<?php

class Pedido
{

    private $numero;
    private $cliente;
    private $produtos = [];

    public function __construct($numero, $cliente)
    {
        $this->numero = $numero;
        $this->cliente = $cliente;
    }

    public function adicionarProduto($produto)
    {
        $this->produtos[] = $produto;
    }

    public function calcularTotal()
    {
        $total = 0;

        foreach ($this->produtos as $produto) {
            $total += $produto->getPreco();
        }

        return $total;
    }

    public function exibirResumo()
    {
        echo "<div class='container'>";

        echo "<h1>Sistema de Pedidos</h1>";
        echo "<h2>Pedido Nº {$this->numero}</h2>";

        echo "<div class='card'>";
        echo "<h3>Cliente</h3>";
        echo "<p>{$this->cliente->getNome()}</p>";
        echo "<p>{$this->cliente->getEmail()}</p>";
        echo "</div>";

        echo "<div class='card'>";
        echo "<h3>Produtos</h3>";

        foreach ($this->produtos as $produto) {
            echo "<p>{$produto->getNome()} - R$ " . number_format($produto->getPreco(), 2, ',', '.') . "</p>";
        }

        echo "</div>";

        echo "<div class='total'>";
        echo "<h3>Total: R$ " . number_format($this->calcularTotal(), 2, ',', '.') . "</h3>";
        echo "</div>";

        echo "</div>";
    }
}
