<?php

use Adianti\Control\TPage;
use Adianti\Widget\Container\TNotebook;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Form\TLabel;
use Adianti\Widget\Util\TXMLBreadCrumb;

class VBoxView extends TPage
{
    public function __construct()
    {
        parent::__construct();
        
        $notebook1 = new TNotebook;
        $notebook2 = new TNotebook;
        
        $notebook1->appendPage('Page1', new TLabel('page 1'));
        $notebook1->appendPage('Page2', new TLabel('page 2'));
        
        $notebook2->appendPage('Page1', new TLabel('page 1'));
        $notebook2->appendPage('Page2', new TLabel('page 2'));
        
        $vbox = new TVBox; // THBox para caixa horizontal
        $vbox->style = 'width:100%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($notebook1);
        $vbox->add($notebook2);
        
        parent::add( $vbox );
    }
}