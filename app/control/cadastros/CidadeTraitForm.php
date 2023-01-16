<?php

use Adianti\Control\TPage;
use Adianti\Control\TAction;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TLabel;
use Adianti\Widget\Wrapper\TDBCombo;
use Adianti\Validator\TRequiredValidator;
use Adianti\Wrapper\BootstrapFormBuilder;

class CidadeTraitForm extends TPage
{// para edição edite a url com method=onEdit
    private $form;
    
    use Adianti\Base\AdiantiStandardFormTrait; //1 habilita o trait
    
    public function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('curso'); //2 diz qual conector
        $this->setActiveRecord('Cidade'); //3 diz qual classe p gravação e ok
        
        $this->form = new BootstrapFormBuilder;
        $this->form->setFormTitle('Cidade');
        $this->form->setClientValidation( true );
        
        $id  = new TEntry('id');
        $nome = new TEntry('nome');
        $estado = new TDBCombo('estado_id', 'curso', 'Estado', 'id', 'nome');
        $id->setEditable(FALSE);
        
        $this->form->addFields( [new TLabel('Id')], [$id] );
        $this->form->addFields( [new TLabel('Nome', 'red')], [$nome] );
        $this->form->addFields( [new TLabel('Estado', 'red')], [$estado] );
        
        $nome->addValidation('Nome', new TRequiredValidator);
        $estado->addValidation('Estado', new TRequiredValidator);
        
        $this->form->addAction('Salvar', new TAction( [$this, 'onSave'] ), 'fa:save green');
        $this->form->addActionLink('Limpar', new TAction( [$this, 'onClear'] ), 'fa:eraser red');
        
        parent::add($this->form);
    }
}