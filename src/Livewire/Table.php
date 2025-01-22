<?php

namespace Thinmoto\Tables\Livewire;

use Illuminate\Support\Arr;
use Thinmoto\Tables\Table\Column;
use Livewire\Component;
use Livewire\WithPagination;

abstract class Table extends Component
{
	use WithPagination;

	private $data;

	protected $paginationView = null;
	protected $cssClass = '';
	protected bool $sortable = false;
	protected $perPage = 10;

	public $sortBy;
	public $sortDirection = 'asc';

	public $selectedItems = [];

    public function render()
    {
        return view('ui::livewire.table');
    }

	### DATA

	public abstract function query();

	public function getData()
	{
		$this->data = $this->query()
			->when($this->sortBy !== '', function ($query) {
				if(!$this->sortBy)
					return $query;
				
				if($query instanceof \Illuminate\Support\Collection)
					return $query->sortBy($this->sortBy, SORT_REGULAR, ($this->sortDirection == 'desc'));

				return $query->orderBy($this->sortBy, $this->sortDirection);
			});

		if($this->perPage)
			$this->data = $this->data->paginate($this->perPage);

		return $this->data;
	}

	### COLUMNS

	/**
	 * @return Column[]
	 */
	public abstract function columns(): array;

	public function sortColumn($key): void
	{
		$this->resetPage();

		if ($this->sortBy === $key)
		{
			$direction = $this->sortDirection === 'asc' ? 'desc' : 'asc';
			$this->sortDirection = $direction;

			return;
		}

		$this->sortBy = $key;
		$this->sortDirection = 'asc';
	}

	### ACTIONS

	public function updateSort($sortData): void
	{

	}

	public function actions(): array
	{
		return [];
	}

	public function hasActions(): bool
	{
		return (bool)count($this->actions());
	}

	public function hasMultiActions(): bool
	{
		foreach($this->actions() as $action)
			if($action->isMulti)
				return true;

		return false;
	}

	public function rowClass($row)
	{
		return '';
	}
}
