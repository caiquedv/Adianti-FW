<?php
//adicionar uma nova janela dentro da pagina
use Adianti\Control\TPage;
use Adianti\Control\TWindow;
use Adianti\Widget\Template\THtmlRenderer;

class OnDemandWindowView extends TPage
{
    public function __construct()
    {
        parent::__construct();
    }

    public function open()
    {
        $window = TWindow::create('título', 0.5, null);

        $html = new THtmlRenderer('app/resources/page.html');

        $replaces = [];
        $replaces['title']  = 'Título';
        $replaces['body']   = 'Conteúdo';
        $replaces['footer'] = 'Rodapé';

        $html->enableSection('main', $replaces);

        $window->add($html);
        $window->show();
    }
}
