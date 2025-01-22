<?php

namespace Thinmoto\Tables\Table;

use Closure;

class Action
{
	public string $tableKey;
	public string $icon = '';
	public string $class = 'primary';
	public string $position = '';
	public string $key;
	public string $label;
	public bool $confirm = false;
	public string $route = '';

	public bool $isMulti = false;

	public $showOnRowClosure;

	public string $livewireEvent;
	public Closure $livewireEventParams;

	public function __construct($key, $label = '', $tableKey = 'table')
	{
		$this->key = $key;
		$this->label = $label;
		$this->tableKey = $tableKey;

		$this->showOnRowClosure = function($row) {
			return true;
		};
	}

	public static function make($key, $label = ''): Action
	{
		return new static($key, $label);
	}

	public function showOnRow($row)
	{
		return call_user_func($this->showOnRowClosure, $row);
	}

	public function setShowOnRow($closure)
	{
		$this->showOnRowClosure = $closure;

		return $this;
	}

	public function setIsMulti(bool $isMulti): Action
	{
		$this->isMulti = $isMulti;

		return $this;
	}

	public function setClass(string $class): Action
	{
		$this->class = $class;

		return $this;
	}

	public function setIcon(string $icon): Action
	{
		$this->icon = $icon;

		return $this;
	}

	public function setConfirm(bool $confirm): Action
	{
		$this->confirm = $confirm;

		return $this;
	}

	public function setRoute(string $action): Action
	{
		$this->route = $action;

		return $this;
	}

	public function setEvent(string $event, ?Closure $params = null): static
	{
		$this->livewireEvent = $event;
		$this->livewireEventParams = $params ?? function($row) {
			return $row->id;
		};;

		return $this;
	}

	public function getEventParams($row)
	{
		$func = $this->livewireEventParams;

		return $func($row);
	}

	public function setPosition(string $position): Action
	{
		$this->position = $position;

		return $this;
	}
}
