@if ($paginator->hasPages())
    <ul class="d-flex justify-content-start list-unstyled overflow-auto">
        @if ($paginator->onFirstPage())
            <li class="btn btn-outline-secondary btn-sm disabled">Prev</li>
        @else
            <li wire:click.prevent="previousPage" class="btn btn-outline-primary btn-sm">Prev</li>
        @endif





        @foreach($elements as $element)
            <div class="justify-content-center d-flex">
                @if(is_array($element))
                    @foreach($element as $page => $url)
                        @if($page == $paginator->currentPage())
                            <li class="btn btn-outline-secondary btn-sm disabled mx-1">{{$page}}</li>
                        @else
                            <li wire:click="gotoPage({{$page}})" class="btn btn-outline-primary btn-sm mx-1">{{$page}}</li>
                        @endif
                    @endforeach
                @endif
            </div>
        @endforeach







        @if ($paginator->hasMorePages())
            <li wire:click.prevent="nextPage" class="btn btn-outline-primary btn-sm">Next</li>
        @else
            <li class="btn btn-outline-secondary btn-sm disabled">Next</li>
        @endif
    </ul>
@endif
