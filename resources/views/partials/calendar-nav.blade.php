<!-- Calendar Navigation -->
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
            <li>{{ $day->date()->format('j') }}</li>
        @endforeach
    @endforeach
</ul>

<!-- End Calendar Navigation -->





