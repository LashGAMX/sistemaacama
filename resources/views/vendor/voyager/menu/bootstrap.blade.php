@php
$menuP = DB::table('ViewMenuUsuarios')
    ->where('parent_id', null)
    ->where('Id_user', Auth::user()->id)
    ->get();
$menuH = DB::table('ViewMenuUsuarios')
    ->where('Id_user', Auth::user()->id)
    ->get();
@endphp

<ul>


    @foreach ($menuP as $item)


        <li class="">
            <a href="{{ $item->route }}" target="{{ $item->target }}" >
                t
                <span>{{ $item->title }}</span>
            </a>
            {{-- @if (!$originalItem->children->isEmpty())
                @include('voyager::menu.default', [
                    'items' => $originalItem->children,
                    'options' => $options,
                ])
            @endif --}}
        </li>
    @endforeach

</ul>
