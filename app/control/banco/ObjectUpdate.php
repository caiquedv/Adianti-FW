<?php

use Adianti\Control\TPage;
use Adianti\Database\TTransaction;
use Adianti\Widget\Dialog\TMessage;

class ObjectUpdate extends TPage
{
    public function __construct()
    {
        parent::__construct();
        
        try
        {
            TTransaction::open('curso');
            
            TTransaction::dump();
            
            $produto = Produto::find( 24 );
            
            if ($produto instanceof Produto)
            {
                $produto->descricao = 'Gravador CD-R';
                $produto->store();
            }
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}