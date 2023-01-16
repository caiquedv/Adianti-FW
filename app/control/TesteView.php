<?php

use Adianti\Control\TPage;

class TesteView extends TPage
{
    public function __construct()
    {
        parent::__construct();

        echo 'construtor <br>';
    }

    public static function onStatic($x)
    {
        //ao usar static=1 na url somente o metodo static é exec
        var_dump($x);
    }
}