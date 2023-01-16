<?php

use Adianti\Control\TPage;
use Adianti\Control\TAction;
use Adianti\Registry\TSession;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TLabel;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Datagrid\TDataGrid;
use Adianti\Widget\Container\TPanelGroup;
use Adianti\Wrapper\BootstrapFormBuilder;
use Adianti\Widget\Datagrid\TDataGridAction;
use Adianti\Widget\Datagrid\TDataGridColumn;
use Adianti\Widget\Datagrid\TPageNavigation;
use Adianti\Wrapper\BootstrapDatagridWrapper;

class CidadeTraitList extends TPage
{
    private $form;
    private $datagrid;
    private $pageNavigation;
    
    use Adianti\Base\AdiantiStandardListTrait; // trait
    
    public function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('curso'); // 1 BD
        $this->setActiveRecord('Cidade'); // 2 classe
        $this->addFilterField('nome', 'like', 'nome'); // 3 filtro do bd (campo_BD, query, campo_form)
        $this->setDefaultOrder('id', 'asc'); // ordem
        
        $this->form = new BootstrapFormBuilder;
        $this->form->setFormTitle('Cidades');
        
        $nome = new TEntry('nome');
        
        $this->form->addFields( [new TLabel('Nome')], [$nome] ); // add campos antes de setData
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') ); // 4 contém os dados da busca
        
        $this->form->addAction('Buscar', new TAction([$this, 'onSearch']), 'fa:search blue');
        $this->form->addActionLink('Limpar', new TAction([$this, 'clear']), 'fa:eraser red'); // 5 limpar
        $this->form->addActionLink('Novo', new TAction( ['CidadeForm', 'onClear']), 'fa:plus-circle green');
        
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->width = '100%';
        
        $col_id     = new TDataGridColumn('id', 'Cód', 'right', '10%');
        $col_nome   = new TDataGridColumn('nome', 'Nome', 'left', '60%');
        $col_estado = new TDataGridColumn('estado->nome', 'Estado', 'center', '30%');
        
        $col_id->setAction( new TAction( [$this, 'onReload'] ), ['order' => 'id']);
        $col_nome->setAction( new TAction( [$this, 'onReload'] ), ['order' => 'nome']);
        
        $this->datagrid->addColumn($col_id);
        $this->datagrid->addColumn($col_nome);
        $this->datagrid->addColumn($col_estado);
        
        $action1 = new TDataGridAction( ['CidadeForm', 'onEdit'], [ 'key' => '{id}'] );
        $action2 = new TDataGridAction( [$this, 'onDelete'], [ 'key' => '{id}'] );
        
        $this->datagrid->addAction( $action1, 'Editar', 'fa:edit blue');
        $this->datagrid->addAction( $action2, 'Excluir', 'fa:trash-alt red');
        
        $this->datagrid->createModel();
        
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction( new TAction([$this, 'onReload'] ));
        
        $panel = new TPanelGroup;
        $panel->add($this->datagrid);
        $panel->addFooter($this->pageNavigation);
        
        $vbox = new TVBox;
        $vbox->style = 'width: 100%';
        $vbox->add($this->form);
        $vbox->add($panel);
        
        parent::add( $vbox );
    }
    
    public function clear()
    {
        $this->clearFilters(); // limpar filtros
        $this->onReload(); // recarrega dg
    }
}