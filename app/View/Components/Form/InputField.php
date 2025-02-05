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
    public $readonly;
    public $class;
    public $options;
    public $required;
    public $disabled;
    public $id;
    public $selectpicker;
    public $liveSearch;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $name,
        $type = 'text',
        $label = null,
        $value = null,
        $placeholder = null,
        $readonly = false,
        $class = 'col-md-4',
        $options = [],
        $required = false,
        $disabled = false,
        $id = null,
        $selectpicker = true,
        $liveSearch = true
    )
    {
        $this->type = $type;
        $this->name = $name;
        $this->label = $label ?? ucfirst($name);
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->readonly = $readonly;
        $this->class = $class;
        $this->options = $options;
        $this->required = $required;
        $this->disabled = $disabled;
        $this->id = $id ?? $name;
        $this->selectpicker = $selectpicker;
        $this->liveSearch = $liveSearch;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.input-field');
    }
}
