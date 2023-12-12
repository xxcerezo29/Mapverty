<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SelectComponent extends Component
{
    public $id;
    public $title;
    public $isRequired;
    public $options;
    public $value;
    /**
     * Create a new component instance.
     */
    public function __construct($id = null, $title = null, $isRequired= null, $options = [], $value = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->isRequired = $isRequired;
        $this->options = $options;
        $this->value = $value;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.select-component');
    }
}
