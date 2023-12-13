<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SurveyQuestionComponent extends Component
{
    public $question;
    public $choices;
    public $type;
    public $isMoney;
    /**
     * Create a new component instance.
     */
    public function __construct($question, $choices = [], $isMoney = false)
    {
        $this->question = $question;
        $this->choices = $choices;
        $this->isMoney = $isMoney;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.survey-question-component');
    }
}
