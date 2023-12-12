<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ChoiceInputComponent extends Component
{
    public $id;
    public $name;
    public $title;
    public $isRequired;
    public $value;
    public $placeholder;
    public $choice_id;
    public $question_id;
    /**
     * Create a new component instance.
     */
    public function __construct($id = null, $title = null, $name= null, $isRequired= null, $value = null, $placeholder = null, $choiceId = null, $questionId = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->title = $title;
        $this->isRequired = $isRequired;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->choice_id = $choiceId;
        $this->question_id = $questionId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.choice-input-component');
    }
}
