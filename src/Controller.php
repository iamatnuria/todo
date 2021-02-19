<?php

namespace App;

use App\DB;
use App\Model;
use App\Session;
use App\View;

abstract class Controller implements View, Model
{
    protected $request;
    protected $session;

    public function __construct($request, $session)
    {
        $this->request = $request;
        $this->session = $session;
    }

    function error($string)
    {
        $this->render(['error'=>$string],'error');
    }

    public function render(?array $dataview = null, ?string $template = null)
    {
        if ($dataview) {
            extract($dataview, EXTR_OVERWRITE);
        }
        if ($template!=null) {
            include 'templates/'.$template.'.tpl.php';
        } else {
            include 'templates/'.$this->request->getController().'.tpl.php';
        }
    }

    public function getDB()
    {
        return DB::singleton();
    }

}
