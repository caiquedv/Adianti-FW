<?php

use Adianti\Control\TPage;
use Adianti\Control\TAction;
use Adianti\Widget\Form\TSortList;
use Adianti\Widget\Wrapper\TDBSortList;
use Adianti\Wrapper\BootstrapFormBuilder;

class SortList extends TPage
{
    public function __construct()
    {
        parent::__construct();
        
        $this->form = new BootstrapFormBuilder;
        $this->form->setFormTitle('Sort List');
        
        $list1 = new TSortList('list1');
        $list2 = new TSortList('list2');
        $list1 = new TDBSortList('list1', 'curso', 'Categoria', 'id', 'nome'); // pega do BD
        
        $list1->addItems( ['a' => 'Opção A', 'b' => 'Opção B', 'c' => 'Opção C' ] ); // add manual
        
        $list1->setSize(200,100);
        $list2->setSize(200,100);
        
        $list1->connectTo($list2);
        $list2->connectTo($list1);
        
        $this->form->addFields( [$list1, $list2] );

        $this->form->addAction('Enviar', new TAction( [$this, 'onSend'] ), 'far:check-circle');
        parent::add( $this->form );
    }
    
    public function onSend($param)
    {
        $data = $this->form->getData();
        $this->form->setData($data);
        
        echo '<pre>';
        var_dump($data->list1);
        var_dump($data->list2);
        echo '</pre>';
    }
}