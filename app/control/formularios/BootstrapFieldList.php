<?php

use Adianti\Control\TPage;
use Adianti\Control\TAction;
use Adianti\Widget\Dialog\TMessage;
use Adianti\Widget\Dialog\TToast;
use Adianti\Widget\Form\TDate;
use Adianti\Widget\Form\TCombo;
use Adianti\Widget\Form\TEntry;
use Adianti\Widget\Form\TFieldList;
use Adianti\Wrapper\BootstrapFormBuilder;

class BootstrapFieldList extends TPage
{
    public function __construct()
    {
        parent::__construct();

        $this->form = new BootstrapFormBuilder('meu_form');
        $this->form->setFormTitle('Lista de campos');

        $combo  = new TCombo('combo[]'); // name[]
        $texto  = new TEntry('texto[]');
        $numero = new TEntry('valor[]');
        $data   = new TDate('dt_registro[]');

        $combo->enableSearch(); // pesquisar options do select
        $combo->addItems(['a' => 'Opção A', 'b' => 'Opção B']);
        $combo->setSize('100%');
        $texto->setSize('100%');
        $numero->setNumericMask(2, ',', '.', true); // true remove a mask qd inserir no BD
        $numero->setSize('100%');
        $numero->style = 'text-align:right';
        $data->setSize('100%');


        $fieldlist = new TFieldList; // incluir, excluir (gerenciar) campos
        $fieldlist->generateAria(); // gera tags aria para acessibilidade
        $fieldlist->width = '100%';
        $fieldlist->addField('<b>Combo</b>',  $combo,  ['width' => '25%']);
        $fieldlist->addField('<b>Texto</b>',  $texto,  ['width' => '25%']);
        $fieldlist->addField('<b>Número</b>', $numero, ['width' => '25%', 'sum' => true]); // true exibe a soma do campos
        $fieldlist->addField('<b>Data</b>',   $data,   ['width' => '25%']);

        $fieldlist->addButtonAction(new TAction([$this, 'showRow']), 'fa:info-circle purple', 'Mostrar Texto'); // add botao de acao p linha, aq usa p wxibir os dados da linha
        // $fieldlist->disableRemoveButton(); // retira o botao de remover, se comentar addcloneaction o form fica com a qtd de linhas fixas
        $fieldlist->setRemoveAction(new TAction([$this, 'showRow'])); // ação ao remover
        $fieldlist->setTotalUpdateAction( new TAction([$this, 'onTotalUpdate'])); // dispara ao atualizar o total

        $fieldlist->enableSorting(); // Drag and Drop

        /*
        $obj = new stdClass;
        $obj->combo = 'a';
        $obj->texto = 'teste';
        $obj->valor = 100;
        $obj->dt_registro = date('Y-m-d');
        */

        $fieldlist->addHeader(); // add cabeçalho
        $fieldlist->addDetail(new stdClass); // add linha (se vazio = stdClass)
        $fieldlist->addDetail(new stdClass);
        $fieldlist->addDetail(new stdClass);
        $fieldlist->addDetail(new stdClass);
        $fieldlist->addDetail(new stdClass);
        // $fieldlist->addCloneAction(); // add botao clone (que inclui e exclui linhas)
        $fieldlist->addCloneAction(new TAction([$this, 'showRow'])); // passando uma acao ele pega os dados da linha anterior a nova incluida

        $this->form->addField($combo); // diz quais campos o form vai gerenciar, aq é necessario pelo uso do adContent
        $this->form->addField($numero);
        $this->form->addField($data);
        $this->form->addField($texto);

        $this->form->addContent([$fieldlist]);

        $this->form->addAction('Enviar', new TAction([$this, 'onSend']), 'fa:save');

        parent::add($this->form);
    }

    public static function onSend($param)
    {
        echo '<pre>';
        var_dump($param);
        echo '</pre>';
    }

    public static function showRow($param)
    {
        new TMessage('info', str_replace(',', '<br>', json_encode($param)));
    }

    public static function onTotalUpdate($param)
    {
        // echo '<pre>';
        // var_dump($param);
        // echo '</pre>';

        $grandTotal = 0;

        if ($param['list_data']) { // list_data é o vetor que contem todas as linhas indexadas p coluna
            foreach ($param['list_data'] as $row) {
                $grandTotal += floatval(str_replace(['.', ','], ['', '.'], $row['valor']));
            }
        }

        TToast::show('info', '<b>Total</b>:' . $grandTotal); // mostra o total flutuando
    }
}
