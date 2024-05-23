<div id="demo" class="carousel slide vertical" data-ride="carousel">
    <!-- Indicators -->
    <ul class="carousel-indicators">
    @foreach($promotions as $key => $promotion)
        <li data-target="#demo" data-slide-to="{{ $key }}" class="{{ ($key == 0) ? 'active' : '' }}"></li>
    @endforeach
    </ul>

    <!-- The slideshow -->
    <div class="carousel-inner">
    @foreach($promotions as $key => $promotion)
        <div class="carousel-item {{ ($key == 0) ? 'active' : '' }}">
            @if(!$promotion->url)
                <img src="{{ store_image($promotion->image) }}" alt="" width="100%">
            @else
            <a href="{{ $promotion->url }}" target="_blank">
                <img src="{{ store_image($promotion->image) }}" alt="" width="100%">
            </a>
            @endif
        </div>
    @endforeach	
    </div>

    <!-- Left and right controls -->
    <!--<a class="carousel-control-prev" href="#demo" data-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </a>
    <a class="carousel-control-next" href="#demo" data-slide="next">
        <span class="carousel-control-next-icon"></span>
    </a>-->
</div>