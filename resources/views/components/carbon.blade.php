<x-ui::base-cell :column="$column">
    @if($date = $column->render($row))
        <div class="table-column-carbon">
            <div>
                {{ $date->format('H:i') }}
            </div>
            <div>
                {{ $date->format('d M Y') }}
            </div>
        </div>
    @endif
</x-ui::base-cell>

