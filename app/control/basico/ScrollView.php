<?php

use Adianti\Control\TPage;
use Adianti\Widget\Container\TScroll;
use Adianti\Widget\Container\TTable;
use Adianti\Widget\Form\TEntry;

class ScrollView extends TPage
{
    public function __construct()
    {
        parent::__construct();
        
        $scroll = new TScroll;
        $scroll->setSize('100%', '300');
        
        $table = new TTable;
        $scroll->add($table);
        
        for ($n=1; $n<=20; $n++)
        {
            $object = new TEntry('field'. $n);
            $table->addRowSet( 'Field '. $n, $object);
        }

        parent::add( $scroll );
    }
}