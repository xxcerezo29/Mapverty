<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputComponent extends Component
{
    public $id;
    public $title;
    public $placeholder;
    public $isRequired;
    public $isError;
    public $errorMessage;

    public $type;
    public $value;
    public $customValidation;
    public $isHidden;
    /**
     * Create a new component instance.
     */
    public function __construct($id, $title, $placeholder, $isRequired, $isError = null, $errorMessage = null, $type = 'text', $value = null, $customValidation = null, $isHidden = "false")
    {
        $this->id = $id;
        $this->title = $title;
        $this->placeholder = $placeholder;
        $this->isRequired = $isRequired;
        $this->isError = $isError;
        $this->errorMessage = $errorMessage;
        $this->type = $type;
        $this->value = $value;
        $this->customValidation = $customValidation;
        $this->isHidden = !($isHidden === 'false');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.input-component');
    }
}
