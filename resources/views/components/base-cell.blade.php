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
    @if(isset($row) && $url = $column->getUrl($row))
        <a href="{{ $url }}">
    @endif

        {{ $slot }}

    @if($url = $column->getUrl())
        </a>
    @endif
</td>

