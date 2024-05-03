<td @class([$column->class, 'cursor-pointer' => $column->hasActions()])
    @if($event = $column->getEvent())
        @click="$dispatch('{{ $event }}', {{ json_encode($column->getEventParams($row)) }})"
    wire:loading.class="disabled"
    @endif

    @if($action = $column->getAction())
        wire:click="{{ $action }}('{{ $row->getKey() }}')"
    wire:loading.class="disabled"
        @endif
>
    {!! $column->render($row) !!}
</td>
