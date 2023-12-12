<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class LineChartComponent extends Component
{
    public $id;
    public $title;
    public $labels;
    public $description;
    public $ajaxURL;
    /**
     * Create a new component instance.
     */
    public function __construct($id, $title = 'title', $labels = 'labels', $ajaxURL = '', $description = 'description')
    {
        $this->id = $id;
        $this->title = $title;
        $this->labels = $labels;
        $this->ajaxURL = $ajaxURL;
        $this->description = $description;
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.line-chart-component');
    }
}
