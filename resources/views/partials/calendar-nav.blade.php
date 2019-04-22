<!-- Calendar Navigation -->
<div class="card border-secondary">
    <div class="card-header">
        {{ $now->format('l, F jS, Y') }} 
    </div>
    <div class="card-body">
        <div class="month">      
            <ul>
                <li class="prev"><a href="{{$previousMonth->format('Y-m-d')}}" class="">&#10094;</a></li>
                <li class="next"><a href="{{$nextMonth->format('Y-m-d')}}" class="">&#10095;</a></li>
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
                    @elseif($searchForDate->isSameDay($day->date()))<li class="current">
                    @elseif($thisMonthsEvents->contains($day->date()))<li class="has-events">
                    @else
                        <li>
                    @endif
                    <a href="{{$day->date()->format('Y-m-d')}}" class="">{{ $day->date()->format('j') }}</a>
                        </li>
                @endforeach
            @endforeach
        </ul>
    </div>
</div>

<!-- End Calendar Navigation -->




