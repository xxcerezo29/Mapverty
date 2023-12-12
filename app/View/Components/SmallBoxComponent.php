<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SmallBoxComponent extends Component
{
    public $id;
    public $title;
    public $text;
    public $icon;
    public $color;
    public $route;
    /**
     * Create a new component instance.
     */
    public function __construct($id = 'smallBox', $title = 'title', $text = 'text', $icon = 'fa-envelope', $color = 'bg-aqua', $route = '')
    {
        $this->id = $id;
        $this->title = $title;
        $this->text = $text;
        $this->icon = $icon;
        $this->color = $color;
        $this->route = $route;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.small-box-component');
    }
}
