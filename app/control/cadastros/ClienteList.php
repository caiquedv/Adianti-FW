<?php

use Adianti\Control\TPage;
use Adianti\Control\TAction;
use Adianti\Registry\TSession;
use Adianti\Widget\Form\TForm;
use Adianti\Widget\Form\TCombo;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Base\TElement;
use Adianti\Widget\Util\TDropDown;
use Adianti\Widget\Datagrid\TDataGrid;
use Adianti\Widget\Container\TPanelGroup;
use Adianti\Widget\Datagrid\TDataGridAction;
use Adianti\Widget\Datagrid\TDataGridColumn;
use Adianti\Widget\Datagrid\TPageNavigation;
use Adianti\Wrapper\BootstrapDatagridWrapper;

class ClienteList extends TPage
{
    private $datagrid;
    private $pageNavigation;
    
    use Adianti\Base\AdiantiStandardListTrait;    
    
    public function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('curso');
        $this->setActiveRecord('Cliente');
        $this->setDefaultOrder('id', 'asc');
        $this->addFilterField('id', '=', 'id'); // addfilterfield relaciona campo_BD com campo_form (faz funcionar a busca)
        $this->addFilterField('nome', 'like', 'nome');
        $this->addFilterField('endereco', 'like', 'endereco');
        $this->addFilterField('genero', '=', 'genero');
        $this->addFilterField('(SELECT nome from cidade where cidade.id = cidade_id)', 'like', 'cidade'); // mesma coisa porem relaciona uma consulta ao campo_form
        $this->setOrderCommand('city->name', '(select nome from cidade where cidade_id = cidade.id)'); // x = p pegar o nome da cidade de outra tabela
        
        $this->datagrid = new BootstrapDatagridWrapper(new TDataGrid);
        $this->datagrid->style = 'width:100%';
        
        $col_id       = new TDataGridColumn('id', 'Cód', 'center', '10%');
        $col_nome     = new TDataGridColumn('nome', 'Nome', 'left', '28%');
        $col_endereco = new TDataGridColumn('endereco', 'Endereço', 'left', '28%');
        $col_cidade   = new TDataGridColumn('{cidade->nome} ({cidade->estado->nome})', 'Cidade', 'left', '28%');
        $col_genero   = new TDataGridColumn('genero', 'Gênero', 'center', '6%');
        
        $col_id->setAction( new TAction([$this, 'onReload']), ['order' => 'id'] );
        $col_nome->setAction( new TAction([$this, 'onReload']), ['order' => 'nome'] );
        $col_endereco->setAction( new TAction([$this, 'onReload']), ['order' => 'endereco'] );
        $col_cidade->setAction( new TAction([$this, 'onReload']), ['order' => 'city->name'] ); // aq usa x
        
        $this->datagrid->addColumn($col_id);
        $this->datagrid->addColumn($col_nome);
        $this->datagrid->addColumn($col_endereco);
        $this->datagrid->addColumn($col_cidade);
        $this->datagrid->addColumn($col_genero);
        
        $col_genero->setTransformer( function($genero) {
            return $genero == 'F' ? 'Feminino' : 'Masculino';
        });
        
        $action1 = new TDataGridAction( ['ClienteForm', 'onEdit'], ['key' => '{id}'] );
        // ↑ register_statep n alterar a url, ja que o edit sera feito em cortina lateral
        $action2 = new TDataGridAction( [$this, 'onDelete'], ['key' => '{id}']);
        
        $this->datagrid->addAction($action1, 'Editar', 'fa:edit blue');
        $this->datagrid->addAction($action2, 'Excluir', 'fa:trash-alt red');
        
        $this->datagrid->createModel();
        
        
        $this->form = new TForm;
        $this->form->add($this->datagrid);
        
        
        $id       =  new TEntry('id');
        $nome     =  new TEntry('nome');
        $endereco =  new TEntry('endereco');
        $cidade   =  new TEntry('cidade');
        $genero   =  new TCombo('genero');
        
        $genero->addItems( [ 'M' => 'Masculino', 'F' => 'Feminino' ] );
        
        $id->exitOnEnter();
        $nome->exitOnEnter();
        $endereco->exitOnEnter();
        $cidade->exitOnEnter();
        
        $id->setSize('100%');
        $nome->setSize('100%');
        $endereco->setSize('100%');
        $cidade->setSize('100%');
        
        $id->tabindex = -1; // tabindex -1 evita foco automatico do cursor
        $nome->tabindex = -1;
        $endereco->tabindex = -1;
        $cidade->tabindex = -1;
        $genero->tabindex = -1;
        
        $id->setExitAction( new TAction( [ $this, 'onSearch' ], ['static' => '1']) );
        $nome->setExitAction( new TAction( [ $this, 'onSearch' ], ['static' => '1']) );
        $endereco->setExitAction( new TAction( [ $this, 'onSearch' ], ['static' => '1']) );
        $cidade->setExitAction( new TAction( [ $this, 'onSearch' ], ['static' => '1']) );
        $genero->setChangeAction( new TAction( [ $this, 'onSearch' ], ['static' => '1']) );
        
        $tr = new TElement('tr');
        $this->datagrid->prependRow($tr); // coloca a linha no começo da dg
        
        $tr->add( TElement::tag('td', '') );
        $tr->add( TElement::tag('td', '') );
        $tr->add( TElement::tag('td', $id) );
        $tr->add( TElement::tag('td', $nome) );
        $tr->add( TElement::tag('td', $endereco) );
        $tr->add( TElement::tag('td', $cidade) );
        $tr->add( TElement::tag('td', $genero) );
        
        $this->form->addField($id); // reconhece os campos para serem passados no post
        $this->form->addField($nome);
        $this->form->addField($endereco);
        $this->form->addField($cidade);
        $this->form->addField($genero);
        
        $this->form->setData( TSession::getValue(__CLASS__.'_filter_data') ); // mantem os dados da pesquisa no campo
        
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction( new TAction( [$this, 'onReload'] ));
        $this->pageNavigation->enableCounters(); // habilita o contador de paginação
        
        
        $panel = new TPanelGroup('Clientes');
        $panel->add($this->form);
        $panel->addFooter($this->pageNavigation);
        
        $dropdown = new TDropDown('Exportar', 'fa:list');
        $dropdown->setButtonClass('btn btn-default waves-effect dropdown-toggle');
        $dropdown->addAction( 'Salvar como CSV', new TAction([$this, 'onExportCSV'], ['register_state' => 'false', 'static'=>'1']), 'fa:table fa-fw blue' );
        $dropdown->addAction( 'Salvar como PDF', new TAction([$this, 'onExportPDF'], ['register_state' => 'false', 'static'=>'1']), 'far:file-pdf fa-fw red' );
        $dropdown->addAction( 'Salvar como XML', new TAction([$this, 'onExportXML'], ['register_state' => 'false', 'static'=>'1']), 'fa:code fa-fw green' );
        
        
        $panel->addHeaderWidget($dropdown);
        $panel->addHeaderActionLink('Novo', new TAction(['ClienteForm', 'onClear'], ['register_state'=>'false']), 'fa:plus green');
        
        
        parent::add( $panel );
    }
}