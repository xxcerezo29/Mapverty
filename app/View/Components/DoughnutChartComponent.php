<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DoughnutChartComponent extends Component
{

    public $id;
    public $title;
    public $labels;
    public $ajaxURL;
    public $ajaxMethod;


    /**
     * Create a new component instance.
     */
    public function __construct($id = 'Doughnut', $title = 'Doughnut', $labels = null, $ajaxURL = null, $ajaxMethod = null)
    {
        $this->id = $id;
        $this->title = $title;
        $this->labels = $labels;
        $this->ajaxURL = $ajaxURL;
        $this->ajaxMethod = $ajaxMethod;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.doughnut-chart-component');
    }
}
