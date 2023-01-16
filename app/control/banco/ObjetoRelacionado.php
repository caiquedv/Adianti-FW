<?php

use Adianti\Control\TPage;
use Adianti\Database\TTransaction;
use Adianti\Widget\Dialog\TMessage;

class ObjetoRelacionado extends TPage
{// buscar info de tabelas relacionadas
    public function __construct()
    {
        parent::__construct();
        
        try
        {
			TTransaction::open('curso');
			TTransaction::dump();
			
			$contatos = Cliente::find(1)->hasMany('Contato'); // hasMany pega a classe cliente e coloca _id e busca em Contato
			$contatos = Cliente::find(1)->hasMany('Contato', 'cliente_id', 'id', 'tipo'); // forma completa
			$contatos = Cliente::find(1)->filterMany('Contato')->where('tipo', '=', 'face')->load();
			$contatos = Cliente::find(1)->filterMany('Contato', 'cliente_id', 'id', 'tipo')->where('tipo', '=', 'face')->load();
			$habilidades = Cliente::find(1)->belongsToMany('Habilidade');
			$habilidades = Cliente::find(1)->belongsToMany('Habilidade', 'ClienteHabilidade', 'cliente_id', 'habilidade_id'); // vendo o ERR da pra entender melhor
			
			TTransaction::close();
        }
        catch (Exception $e)
        {
            new TMessage('error', $e->getMessage());
        }
    }
}