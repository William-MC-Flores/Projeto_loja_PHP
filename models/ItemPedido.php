<?php

class ItemPedido {

    private $produto;
    private $quantidade;
    private $subtotal;

    public function __construct($produto, $quantidade) {
        $this->produto = $produto;
        $this->quantidade = $quantidade;
        $this->subtotal = $produto->getPreco() * $quantidade;
    }

    public function getProduto() {
        return $this->produto;
    }

    public function getQuantidade() {
        return $this->quantidade;
    }

    public function getSubtotal() {
        return $this->subtotal;
    }
}