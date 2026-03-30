<?php

class Pedido {

    private $id;
    private $cliente;
    private $itens;
    private $total;

    public function __construct($id, $cliente) {
        $this->id = $id;
        $this->cliente = $cliente;
        $this->itens = [];
        $this->total = 0;
    }

    public function getId() {
        return $this->id;
    }

    public function getCliente() {
        return $this->cliente;
    }

    public function getItens() {
        return $this->itens;
    }

    public function getTotal() {
        return $this->total;
    }

    public function adicionarItem($item) {
        $this->itens[] = $item;
        $this->total += $item->getSubtotal();
    }
}