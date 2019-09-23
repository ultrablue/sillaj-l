<div class="w-1/6 text-xs border">
    <div class="m-2 border">
        <div class="flex justify-between">
            <div>
                <a href="{{$previousMonth->format('Y-m-d')}}" class="">
                    <span class="oi" data-glyph="chevron-left"></span>
                </a>
            </div>
            <div><h1 class="font-bold">{{ $month->title('Y-m F') }}</h1></div>
            <div>
                <a href="{{$nextMonth->format('Y-m-d')}}" class="">
                    <span class="oi" data-glyph="chevron-right"></span>
                </a>
            </div>
        </div>
    </div>

    <div class="flex border-b border-black">
        <div class="w-1/7 border-black text-center">Mo</div>
        <div class="w-1/7 border-black text-center">Tu</div>
        <div class="w-1/7 border-black text-center">We</div>
        <div class="w-1/7 border-black text-center">Th</div>
        <div class="w-1/7 border-black text-center">Fr</div>
        <div class="w-1/7 border-black text-center">Sa</div>
        <div class="w-1/7 border-black text-center">Su</div>
    </div>


    @foreach ($month->weeks() as $week)
        <div class="flex">
            @foreach ($week->days() as $day)
                @php
                    $calendarClasses = ["w-1/7", "border-black", "text-center"];
                    if ($day->isOverflow()) {
                        $calendarClasses[] = 'text-gray-500';
                    }
                    elseif($now->isSameDay($day->date())) {
                        $calendarClasses[] = 'bg-green-500';
                    }
                    elseif($thisMonthsEvents->contains($day->date())) {
                        $calendarClasses[] = 'bg-blue-500';
                    }
                @endphp
                <div class="{{implode(' ', $calendarClasses)}}">
                    <a href="{{$day->date()->format('Y-m-d')}}" class="">{{ $day->date()->format('j') }}</a>
                </div>
            @endforeach
        </div>
    @endforeach


</div>




