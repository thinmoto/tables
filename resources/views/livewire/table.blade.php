<div
        x-data="Table($refs.table)"
>
    @yield('before')

    <div class="table-view" wire:loading.delay.class="ajax-loading">
        <table class="table {{ $this->cssClass }}" x-ref="table table-striped">
            <thead><tr>
                @if($this->hasMultiActions())
                    <th align="center" width="1%" nowrap>
                        <label class="checkbox">
                            <input
                                type="checkbox"
                                data-type="table-actions-all"
                                @checked(!empty($selectedItems) && count($selectedItems))
                            >
                        </label>
                    </th>
                @endif

                @foreach($this->columns() as $column)
                    @if($column->hasSorting())
                        <th class="cursor-pointer" wire:click="sortColumn('{{ $column->getKey() }}')" nowrap>
                            {{ $column->getLabel() }}

                            @if($sortBy === $column->getKey())
                                @if ($sortDirection === 'asc')
                                    <i class="fas fa-sort-up"></i>
                                @else
                                    <i class="fas fa-sort-down"></i>
                                @endif
                            @else
                                <i class="fas fa-sort"></i>
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
            @forelse($this->getData() as $row)
                <tr
                        @if($this->sortable)
                            class="sortable-tr"
                            x-sortable-item="{{ $row->getKey() }}"
                        @endif
                        class="{{ $this->rowClass($row) }}"
                >
                    @if($this->hasMultiActions())
                        <td align="center" width="1%" nowrap>
                            <label class="checkbox">
                                <input
                                        type="checkbox"
                                        data-type="table-actions-all"
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
                                    :column="$column"
                                    :row="$row"
                            >
                            </x-dynamic-component>
                        @endif
                    @endforeach

                    @if($this->hasActions())
                        <td nowrap="">
                            @foreach($this->actions() as $action)
                                @if($action->showOnRow($row))
                                    <a
                                            wire:key="{{ rand() }}"
                                            class="btn btn-{{ $action->class }} btn-sm"

                                            @if($action->route)
                                                href="{{ route($action->route, $row->getKey()) }}"
                                            @elseif(!empty($action->livewireEvent))
                                                wire:click="$dispatch('{{ $action->livewireEvent }}', {{ json_encode($action->getEventParams($row)) }})"
                                            @else
                                                @if($action->confirm)
                                                    x-data=""
                                                    x-on:click="if(confirm('{{ __('app.confirm_action') }}')) $wire.{{ $action->key }}('{{ $row->getKey() }}');"
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
                                @endif
                            @endforeach
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

        {{ $this->getData()->links($this->paginationView) }}
    </div>

    <div class="table-view-after">
        @yield('after')
    </div>
</div>
