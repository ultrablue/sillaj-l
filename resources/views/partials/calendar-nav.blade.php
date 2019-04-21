<!-- Calendar Navigation -->
<div class="card border-secondary">
    <div class="card-header">
        {{ $now->format('l, F jS, Y') }} 
    </div>
    <div class="card-body">
        <div class="month">      
            <ul>
                <li class="prev">&#10094;</li>
                <li class="next">&#10095;</li>
                <li>{{ $month->title('Y-m F') }}</li>
            </ul>
        </div>

        <ul class="weekdays">
            @foreach ($month->weeks()[0]->days() as $day)
                <li>{{ $day->date()->format('D') }}</li>
            @endforeach
        </ul>


        <ul class="days">
            @foreach ($month->weeks() as $week)
                @foreach ($week->days() as $day)
                    @if ($day->isOverflow())<li class="overflow">
                    @elseif($now->isSameDay($day->date()))<li class="active">
                    @else
                        <li>
                    @endif
                    <a href="{{$day->date()->format('Y-m-d')}}" class="">{{ $day->date()->format('j') }}</a>
                        </li>
                    </li>
                @endforeach
            @endforeach
        </ul>
    </div>
</div>

<!-- End Calendar Navigation -->




