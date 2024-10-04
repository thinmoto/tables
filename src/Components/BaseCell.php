<?php

namespace Thinmoto\Tables\Components;

use Thinmoto\Tables\Table\Column;

class BaseCell extends \Illuminate\View\Component
{
	public function __construct(
		Column $column,
		mixed $row
	) {}

	public function render()
	{
		return view('ui::components.base-cell');
	}
}
