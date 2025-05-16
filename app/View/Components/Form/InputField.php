<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputField extends Component
{
    public $type;
    public $name;
    public $label;
    public $value;
    public $placeholder;
    public $class;
    public $options;
    public $id;
    public $rows;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $name,
        $type = 'text',
        $label = null,
        $value = null,
        $placeholder = null,
        $class = 'col-md-4',
        $options = [],
        $id = null,
        $rows = 3
    )
    {
        $this->type = $type;
        $this->name = $name;
        $this->label = $label ?? ucfirst(str_replace('_', ' ', $name));
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->class = $class;
        $this->options = $options;
        $this->id = $id ?? $name;
        $this->rows = $rows;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.input-field');
    }
}
