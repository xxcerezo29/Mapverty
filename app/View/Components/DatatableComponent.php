<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DatatableComponent extends Component
{

    public $id;
    public $columns;
    public $data_display;
    public $url;
    public $addUrl;
    public $addBtnText;
    public $removeUrl;
    public $excelUrl;

    /**
     * Create a new component instance.
     */
    public function __construct($id = null, $columns = null, $url = null, $addUrl = null, $removeUrl = null, $data = null, $addBtnText = null, $excelUrl = null)
    {
        $this->id = $id;
        $this->columns = $columns;
        $this->url = $url;
        $this->addUrl = $addUrl;
        $this->removeUrl = $removeUrl;
        $this->data_display = $data;
        $this->addBtnText = $addBtnText;
        $this->excelUrl = $excelUrl;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.datatable-component');
    }
}
