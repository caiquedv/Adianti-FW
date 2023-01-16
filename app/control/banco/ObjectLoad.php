<?php

use Adianti\Control\TPage;
use Adianti\Database\TTransaction;
use Adianti\Widget\Dialog\TMessage;

class ObjectLoad extends TPage
{ // no load caso nao encontre ele aborta -> use find p/ continuar execução (ObjectFind)
    public function __construct()
    {
        parent::__construct();
        
        try
        {
            TTransaction::open('curso');
            
            TTransaction::dump();
            
            $produto = new Produto( 8876 );
            
            echo '<b>Descrição</b>: ' . $produto->descricao;
            echo '<br>';
            echo '<b>Estoque</b>: ' . $produto->estoque;
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}