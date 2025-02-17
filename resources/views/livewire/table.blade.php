<div
        x-data="Table($refs.table)"
>
    @yield('before')

    <div class="table-view" wire:loading.delay.class="ajax-loading">
        <div class="table-wrap">
            <table class="table {{ $this->cssClass }}" x-ref="table table-striped">
            <thead><tr>
                @if($this->hasMultiActions())
                    <th align="center" width="1%" nowrap>
                        <label class="checkbox">
                            <input
                                type="checkbox"
                                class="form-check-input form-check-input-lg fs-5"
                                @checked(!empty($selectedItems) && count($selectedItems))
                            >
                        </label>
                    </th>
                @endif

                @foreach($this->columns() as $column)
                    @if($column->hasSorting())
                        <th role="button" wire:click="sortColumn('{{ $column->getKey() }}')" nowrap>
                            {{ $column->getLabel() }}

                            @if($sortBy === $column->getKey())
                                @if ($sortDirection === 'asc')
                                    <i class="fa-solid fa-arrow-down-a-z text-muted opacity-75"></i>
                                @else
                                    <i class="fa-solid fa-arrow-down-z-a text-muted opacity-75"></i>
                                @endif
                            @else
                                <i class="fas fa-sort text-muted opacity-75"></i>
                            @endif
                        </th>
                    @else
                        <th>{{ $column->getLabel() }}</th>
                    @endif
                @endforeach

                @if($this->hasActions())
                    <th align="center" width="1%" nowrap>
                    </th>
                @endif
            </tr>
            </thead>

            <tbody
                    @if($this->sortable)
                        x-sortable
                        x-on:sorted="$wire.updateSort($event.detail)"
                    @endif
            >
            @php $sortCounter = 0; @endphp
            @forelse($this->getData() as $row)
                @php $sortCounter++; @endphp
                <tr
                        @if($this->sortable)
                            class="sortable-tr"
                            x-sortable-item="{{ $sortCounter }}"
                        @endif
                        class="{{ $this->rowClass($row) }}"
                >
                    @if($this->hasMultiActions())
                        <td align="center" width="1%" nowrap>
                            <label class="checkbox">
                                <input
                                        type="checkbox"
                                        class="form-check-input form-check-input-lg fs-5"
                                        data-type="table-actions-all"
                                        wire:model.live="selectedItems.{{ $sortCounter }}"
                                        @checked(!empty($selectedItems) && count($selectedItems))
                                >
                            </label>
                        </td>
                    @endif

                    @foreach($this->columns() as $column)


                        @if($column->hasLivewire())
                            <td>
                                @livewire($column->livewire, $column->getLivewireParams($row), key(rand()))
                            </td>
                        @else
                            <x-dynamic-component
                                    :component="$column->getComponent()"
                                    :options="$column->getComponentOptions()"
                                    :column="$column"
                                    :row="$row"
                            >
                            </x-dynamic-component>
                        @endif
                    @endforeach

                    @if($this->hasActions())
                        <td nowrap="">
                            @if($this->dropdownActions)
                                <div class="dropdown">
                                    <button class="btn btn-sm" type="button" x-on:click="$el.classList.add('btn-light'); setTimeout(function(){ $el.nextElementSibling.classList.add('show') }, 10)">
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </button>
                                    <ul class="dropdown-menu" data-type="table-action-menu" style="right:0" @click.away="$el.classList.remove('show'); $el.previousElementSibling.classList.remove('btn-light')">
                            @endif

                            @foreach($this->actions() as $action)
                                @if($action->showOnRow($row))
                                    @if($this->dropdownActions)
                                        <li>
                                    @endif

                                    <a
                                            wire:key="{{ rand() }}"
                                            class="{{ $this->dropdownActions ? 'dropdown-item text-'.$action->class : 'btn btn-sm btn-'.$action->class }}"

                                            @if($action->route)
                                                href="{{ route($action->route, $row->getKey()) }}"
                                            @elseif(!empty($action->livewireEvent))
                                                @if($action->confirm)
                                                    x-data=""
                                                    x-on:click="if(confirm('{{ __('ui::tables.confirm_action') }}')) $wire.dispatch('{{ $action->livewireEvent }}', {{ json_encode($action->getEventParams($row)) }});"
                                                @else
                                                    wire:click="$dispatch('{{ $action->livewireEvent }}', {{ json_encode($action->getEventParams($row)) }})"
                                                @endif
                                            @else
                                                @if($action->confirm)
                                                    x-data=""
                                                    x-on:click="if(confirm('{{ __('ui::tables.confirm_action') }}')) $wire.{{ $action->key }}('{{ $row->getKey() }}');"
                                                @else
                                                    wire:click="{{ $action->key }}('{{ $row->getKey() }}')"
                                                @endif
                                            @endif
                                    >
                                        @if($action->icon)
                                            <i class="{{ $action->icon }}"></i>
                                        @endif

                                        {{ $action->label }}
                                    </a>

                                    @if($this->dropdownActions)
                                        </li>
                                    @endif
                                @endif
                            @endforeach

                            @if($this->dropdownActions)
                                    </ul>
                                </div>
                            @endif
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="100">{{ __('app.empty_table_data') }}</td>
                </tr>
            @endforelse
            </tbody>
        </table>
        </div>

        {{ $this->getData()->links($this->paginationView) }}
    </div>

    <div class="table-view-after">
        @yield('after')
    </div>
</div>
