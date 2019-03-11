@extends('layouts.app')

@section('content')

    

    <div class="container" style="margin-top:30px">

        <div class="row">
            <div class="col-sm-12">
                <p>
                    <h2>Todo:</h2>
                    <ul>
                        <li>Complete the home route to get all events for the given date, and for today if no date given.</li>
                        <li>Fix the form. It should be much more compact.</li>
                        <li>Add the Events list</li>
                        <li>Create the Calendar nav section</li>
                    </ul>
                </p>
            </div>
        </div>



        <div class="row"><!-- Top row -->

            <div class="col-sm-4" style="background:cornsilk;"><!-- Calendar Nav -->
                Calendar Nav
                @include('partials.calendar-nav')                
            </div><!-- /Calendar Nav -->





            <div class="col-sm-8" style="background:rosybrown;"><!-- Entry Form -->
                Entry Form
		@include('partials.event-form')
	    </div><!-- /Entry Form -->


        </div><!-- /Top row -->




        <div class="row"><!-- Second Row -->
            <div class="col-sm-12" style="background: khaki;"><!-- Event List -->
                Event List
            </div><!-- /Event List -->
        </div><!-- /Second Row -->

    </div><!-- /container -->




@endsection
