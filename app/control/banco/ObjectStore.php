<?php

use Adianti\Control\TPage;
use Adianti\Database\TTransaction;
use Adianti\Widget\Dialog\TMessage;

class ObjectStore extends TPage
{
    public function __construct()
    {
        parent::__construct();
        echo 'Descomente o código para gravar um novo produto';
        try
        {
            TTransaction::open('curso');
            
            /*
            TTransaction::setLoggerFunction( function($mensagem) {
                print $mensagem . '<br>';
            });
            */ // exibir log em tela
            
            TTransaction::dump('tmp/log.txt'); // passar o caminho no dump para gravar log
            TTransaction::dump(); // sem caminho para exibir em tela
            
            $produto = new Produto;
            $produto->descricao = 'GRAVADOR DVD';
            $produto->estoque = 10;
            $produto->preco_venda = 100;
            $produto->unidade = 'PC';
            $produto->local_foto =  '';
            $produto->store();
            
            new TMessage('info', 'Produto gravado com sucesso');
            
            TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}