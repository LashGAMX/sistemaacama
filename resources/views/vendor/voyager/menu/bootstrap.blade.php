@if(!isset($innerLoop))
<ul class="dd-list">
@else
<ul class="dropdown-menu">
@endif

@php

    if (Voyager::translatable($items)) {
        $items = $items->load('translations');
    } 

@endphp
@foreach ($items as $item)

    @php

        $originalItem = $item;
        if (Voyager::translatable($item)) {
            $item = $item->translate($options->locale);
        }

        $listItemClass = null;
        $linkAttributes =  null;
        $styles = null;
        $icon = null;
        $caret = null;

        // Background Color or Color
        if (isset($options->color) && $options->color == true) {
            $styles = 'color:'.$item->color;
        }
        if (isset($options->background) && $options->background == true) {
            $styles = 'background-color:'.$item->color;
        }

        // With Children Attributes
        if(!$originalItem->children->isEmpty()) {
            $linkAttributes =  'class="dropdown-toggle" data-toggle="dropdown"';
            $caret = '<span class="caret"></span>';

            if(url($item->link()) == url()->current()){
                $listItemClass = 'dropdown active';
            }else{
                $listItemClass = 'dropdown';
            }
        }

        // Set Icon
        if(isset($options->icon) && $options->icon == true){
            $icon = '<i class="' . $item->icon_class . '"></i>';
        }
 
    @endphp
<div class="accordion" id="accordionExample" style="background: #353d47">

    <div class="card"> 
      <div class="card-header" id="headingTwo" style="background: #353d47">
        <h2 class="mb-0">
          <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
            <a href="{{ url($item->link()) }}" target="{{ $item->target }}" style="{{ $styles }} color:#fff" {!! $linkAttributes ?? '' !!}>
                <i class="{{$item->icon_class}}"></i>&nbsp;{{ $item->title }}</span>
            </a>
          </button>
        </h2>
      </div>
      @if(!$originalItem->children->isEmpty())
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
            <div class="card-body">
                @foreach ($originalItem->children as $item2)
                <div class="card">
                    <div class="card-header" id="headingTwo">
                      <h2 class="mb-0">
                        <a href="{{ url($item2->link()) }}" style="font-size:50%;color:#fff;"><i class="{{$item2->icon_class}}"></i>&nbsp;<span>{{ $item2->title }}</span></a>
                      </h2>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
      @endif
    </div>

</div>
@endforeach

</ul>

