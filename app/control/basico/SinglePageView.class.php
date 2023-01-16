<?php

use Adianti\Control\TPage;
use Adianti\Widget\Container\TVBox;
use Adianti\Widget\Template\THtmlRenderer;
use Adianti\Widget\Util\TXMLBreadCrumb;

class SinglePageView extends TPage
{
    public function __construct()
    {
        parent::__construct();
        
        $html = new THtmlRenderer('app/resources/page.html');
        
        $replaces = [];
        $replaces['title']  = 'Título';
        $replaces['body']   = 'Conteúdo';
        $replaces['footer'] = 'Rodapé';
        
        $html->enableSection('main', $replaces);
        
        $vbox = new TVBox;
        $vbox->style = 'width:100%';
        $vbox->add( new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($html);
        
        parent::add($vbox);
    } 
}