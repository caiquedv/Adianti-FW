<?php

use Adianti\Control\TPage;
use Adianti\Widget\Base\TElement;

class EmbeddedPDFView extends TPage
{
    public function __construct()
    {
        parent::__construct();
        
        $object = new TElement('iframe'); // iframe permite embarcar conteudo externo
        $object->width  = '100%';
        $object->height = '600px';
        $object->src    = 'http://localhost/adianti/framework/Adianti_Framework.pdf';
        $object->type   = 'application/pdf';

        parent::add( $object );
    }
}