<?php

use Adianti\Control\TPage;
use Adianti\Database\TTransaction;
use Adianti\Widget\Dialog\TMessage;

class ObjectDelete extends TPage
{
    public function __construct()
    {
        parent::__construct();
        // erro sql aqui é pelo relacionamento do produto em outra tabela
        // crie um novo produto para poder excluir
        try {
            TTransaction::open('curso');

            TTransaction::dump();

            $produto = Produto::find(25);

            if ($produto instanceof Produto) {
                $produto->delete();
                new TMessage('info', 'Produto Excluído');
            } // faz o select e exclui

            /*
            $produto = new Produto;
            $produto->delete( 28 );
            */
            // só exclui

            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }
}
