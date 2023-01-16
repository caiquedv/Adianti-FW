<?php

use Adianti\Control\TPage;
use Adianti\Control\TAction;
use Adianti\Widget\Form\TForm;
use Adianti\Widget\Form\TCombo;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TLabel;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Wrapper\BootstrapFormBuilder;

class FormularioInteracoes extends TPage
{
    private $form;
    
    public function __construct()
    {
        parent::__construct();
        
        $this->form = new BootstrapFormBuilder('meu_form');
        $this->form->setFormTitle('Interações');
        
        $input_a = new TEntry('input_a');
        $input_b = new TEntry('input_b');
        
        $combo_a = new TCombo('combo_a');
        $combo_b = new TCombo('combo_b');
        
        $input_a->setExitAction( new TAction( [$this, 'onExitAction'] ) ); // define acao na saida de campo (metodos estaticos)
                                                                           // normalmente inputs
        $combo_a->setChangeAction( new TAction( [$this, 'onChangeAction'] ) ); // define acao na troca de valor
                                                                           // normalmente seleções        
        $combo_a->addItems( [ 'a' => 'Opção A', 'b' => 'Opção B', 'c' => 'Opção C'] );
        
        $this->form->addFields( [new TLabel('Input A')], [$input_a] );
        $this->form->addFields( [new TLabel('Input B')], [$input_b] );
        $this->form->addFields( [new TLabel('Combo A')], [$combo_a] );
        $this->form->addFields( [new TLabel('Combo B')], [$combo_b] );

        parent::add($this->form);
    }
    
    public static function onExitAction($param)
    {// ao sair do campo envia um valor para outro
        $obj = new stdClass;
        $obj->input_b = "Você digitou " . $param['input_a'] . ' ' . date('H:i:s'); 
        
        TForm::sendData('meu_form', $obj); // faz o envio (nome_form, dados)
        
        new TMessage('info', 'Você digitou '. $param['input_a']);
    }
    
    public static function onChangeAction($param)
    { // ao trocar o valor outro é enviado para o outro campo
        $opcoes = [];
        $opcoes[1] = $param['combo_a'] . ' - Um';
        $opcoes[2] = $param['combo_a'] . ' - Dois';
        $opcoes[3] = $param['combo_a'] . ' - Três';
        
        TCombo::reload('meu_form', 'combo_b', $opcoes); // recarrega com dados (nome_form, campo_destino, dados)
    }
}
