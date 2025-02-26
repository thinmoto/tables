import Sortable from 'sortablejs'

export default function (Alpine) {
    Alpine.directive('sortable', (el) => {
        el.sortable = Sortable.create(el, {
            dataIdAttr: 'x-sortable-item',
            draggable: '.x-sortable-handle',
            onSort() {
                el.dispatchEvent(
                    new CustomEvent('sorted', {
                        detail: el.sortable.toArray().map(id => parseInt(id))
                    })
                )
            }
        })
    })
}
