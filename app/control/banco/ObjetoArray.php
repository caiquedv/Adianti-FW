<?php

use Adianti\Control\TPage;
use Adianti\Database\TTransaction;
use Adianti\Widget\Dialog\TMessage;

class ObjetoArray extends TPage
{
    public function __construct()
    {
        parent::__construct();
        
        try
        {
            TTransaction::open('curso');
            
            $produto = new Produto(3);
    // nesse ex carrega um obj como arry e dps alimenta outra obj com esse array
            echo '<pre>';
            print_r( $produto->toArray() ); // conv obj->array
            echo '</pre>';
            
            $dados = [];
            $dados['descricao'] = 'Smart Watch';
            $dados['estoque'] = 2;
            $dados['preco_venda'] = 200;
            $dados['unidade'] = 'PC';
            
            $produto = new Produto;
            $produto->fromArray( $dados );  // conv array->obj
            $produto->store();
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}