<?php

namespace Thinmoto\Tables\Table;

use Closure;
use Thinmoto\Tables\Components\BaseCell;

class Column
{
    private string $key;
    private string $label;

    private bool $sort = false;

    private Closure $processor;
    private Closure $actionProcessor;

    private string $event;
    private Closure $eventProcessor;

    private string $action = '';

	private string $component;
	private array $componentOptions;

    private string $livewire = '';
    private Closure $livewireParamsMaker;

	public string $class = '';

    public function __construct(string $key, string $label = '')
    {
        $this->key = $key;
        $this->label = $label;
        $this->component = 'ui::cell';

        $this->processor = function($row, $key) {
            if(is_array($row))
                return $row[$key];

            return $row->{$key};
        };

        $this->actionProcessor = function($row, $key) {
            if(is_array($row))
                return ['id' => $row[$key]];

            return ['id' => $row->{$key}];
        };

        $this->eventProcessor = function($row, $key) {
            if(is_array($row))
                return ['id' => $row[$key]];

            return [$row->getKey()];
        };
    }

    public static function make($key, $label = ''): static
    {
        return new static($key, $label);
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function hasSorting(): bool
    {
        return $this->sort;
    }

    public function enableSorting(): static
    {
        $this->sort = true;

        return $this;
    }

    public function setLivewire(string $component, $paramsMaker): static
    {
        $this->livewire = $component;
        $this->livewireParamsMaker = $paramsMaker;

        return $this;
    }

    public function hasLivewire(): bool
    {
        return (bool)$this->livewire;
    }

    public function setAction(string $action, $actionProcessor = null): static
    {
        $this->action = $action;

        if(!empty($actionProcessor))
            $this->actionProcessor = $actionProcessor;

        return $this;
    }

    public function setEvent(string $event, $eventProcessor = null): static
    {
        $this->event = $event;

        if(!empty($eventProcessor))
            $this->eventProcessor = $eventProcessor;

        return $this;
    }

    public function render($row)
    {
        $func = $this->processor;

        return $func($row, $this->key);
    }

    public function getAction()
    {
	    return !empty($this->action) ? $this->action : null;
    }

    public function getActionParams($row)
    {
        $func = $this->actionProcessor;

        return $func($row, $this->key);
    }

    public function getEvent()
    {
        return !empty($this->event) ? $this->event : null;
    }

    public function getEventParams($row)
    {
        $func = !empty($this->eventProcessor) ? $this->eventProcessor : null;

        return $func ? $func($row, $this->key) : null;
    }

	public function setClass($class)
	{
		$this->class = $class;

		return $this;
	}

	public function setProcessor(Closure $processor): static
	{
		$this->processor = $processor;

		return $this;
	}

	public function hasActions()
	{
		return false;
	}

	/**
	 * Set component to display cell. Must be located in resources/views/components
	 *
	 * @param string $component
	 * @param array $options
	 * @return $this
	 */
	public function setComponent(string $component, array $options = []): static
	{
		$this->component = $component;
		$this->componentOptions = $options;

		return $this;
	}

	public function getComponent(): string
	{
		return $this->component;
	}

	public function getComponentOptions(): array
	{
		return $this->componentOptions;
	}
}
