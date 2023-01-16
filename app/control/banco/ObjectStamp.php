<?php

use Adianti\Control\TPage;
use Adianti\Database\TTransaction;
use Adianti\Widget\Dialog\TMessage;

class ObjectStamp extends TPage 
    // carimbo de tempo
{   // com com CREATEDAT e UPDATEDAT habilitados no model, o framework grava a hora da
    // inserção e atualização

    // funções gancho comentadas no model Cliente <--
    public function __construct()
    {
        parent::__construct();
        
        try
        {
            TTransaction::open('curso');
            
            TTransaction::dump();
            
            
            // $cliente = new Cliente;
            // $cliente->nome = 'Registro teste';
            // $cliente->endereco = 'Rua teste';
            // $cliente->telefone = '123123';
            // $cliente->categoria_id =1;
            // $cliente->cidade_id = 1;
            // $cliente->store();

            // new TMessage('info', 'Cliente gravado com sucesso');
            
            $cliente = Cliente::find(41);
            $cliente->nome = 'Registro teste alterado';
            $cliente->store();
            
            new TMessage('info', 'Cliente atualizado com sucesso');
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}