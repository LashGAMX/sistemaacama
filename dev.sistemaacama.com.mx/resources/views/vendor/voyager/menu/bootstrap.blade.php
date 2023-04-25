@php
$menuP = DB::table('ViewMenuUsuarios')
    ->where('parent_id', null)
    ->where('Id_user', Auth::user()->id)
    ->get();
$menuH = DB::table('ViewMenuUsuarios')
    ->where('Id_user', Auth::user()->id)
    ->get();
@endphp

<ul class="nav">


    @foreach ($menuP as $item)


        <li class="nav-item">
            <a target="{{ $item->target }}" data-toggle="collapse" href="#colls{{ $item->id }}" role="button" aria-expanded="false" aria-controls="colls{{ $item->id }}">
                <i class="{{ $item->icon_class }}"></i><span> {{ $item->title }}</span>
            </a>
        </li>
        <div class="collapse" id="colls{{ $item->id }}">
            <div class="card card-body">
                <ul class="nav">
                @foreach ($menuH as $item2)
                    @if ($item->id == $item2->parent_id)
                        <li class="nav-item">
                            <a href="@if ($item2->url != '')
                                {{ url($item2->url) }}
                            @else
                                {{ route($item2->route) }}
                            @endif" target="{{ $item->target }}">
                                <i class="{{ $item2->icon_class }}"></i><span> {{ $item2->title }}</span>
                            </a>
                        </li>           
                    @endif
                @endforeach
                </ul>
            </div>
        </div>
    @endforeach

</ul>
