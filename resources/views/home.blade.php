@extends('layouts.app')

@section('content')

    

    <div class="container" style="margin-top:30px">

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
