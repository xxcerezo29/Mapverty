<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ModalComponent extends Component
{
    public $id;
    public $title;
    public $isForm;
    public $action;
    public $method;
    public $static;

    public $reloadWhenSubmit;

    public $datatable;
    public $submitBtnText;
    /**
     * Create a new component instance.
     */
    public function __construct($id, $title, $action, $method, $submitBtnText, $isForm = null, $datatable = null, $reloadWhenSubmit = false, $static = "false")
    {
        $this->id = $id;
        $this->title = $title;
        $this->isForm = $isForm;
        $this->action = $action?? '';
        $this->method = $method?? '';
        $this->submitBtnText = $submitBtnText === ''? 'submit_btn': $submitBtnText;
        $this->datatable = $datatable?? '';
        $this->static = !($static === 'false');
        if($this->isForm === false){
            $this->reloadWhenSubmit = false;
        }else {
            $this->reloadWhenSubmit = $reloadWhenSubmit;
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.modal-component');
    }
}
