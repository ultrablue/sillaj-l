<div class="border-gray-300 border w-1/2 m-2 text-xs">

    <form style="" action="{{action('EventController@store')}}" method="post">
        <div class="flex px-3">
            <div class="mb-4 mr-2">
                <label class="block text-gray-700 text-sm" for="date">
                    Date
                </label>
                <input class="text-field focus:outline-none focus:shadow-outline" id="date" name="event_date" type="text" placeholder="yyyy-mm-dd">
            </div>

            <div class="mb-4 mr-2">
                <label class="block text-gray-700 text-sm" for="start-time">
                    Start
                </label>
                <input size="5" class="text-field focus:outline-none focus:shadow-outline" id="start" name="time_start" type="text" placeholder="24:00">
            </div>

            <div class="mb-4 mr-2">
                <label class="block text-gray-700 text-sm" for="end-time">
                    End
                </label>
                <input size="5" class="text-field focus:outline-none focus:shadow-outline" id="end" name="time_end" type="text" placeholder="24:00">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm" for="duration">
                    Duration
                </label>
                <input size="5" class="text-field focus:outline-none focus:shadow-outline" id="duration" name="duration" type="text" placeholder="24:00">
            </div>


        </div>


        <div>


            <div class="flex flex-row">

                <div class="px-3">
                    <label class="block text-gray-700 text-sm" for="project">
                        Project
                    </label>
                    <div class="relative">
                        <select class="block appearance-none bg-gray-200 border border-gray-200 text-gray-700 py-1 px-1 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="project" name="project_id">
                            @foreach($projects as $project)
                                <option value="{{$project->id}}">{{$project->name}}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="px-3 mb-6 ">
                    <label class="block text-gray-700 text-sm" for="task">
                        Task
                    </label>
                    <div class="relative">
                        <select class="block appearance-none bg-gray-200 border border-gray-200 text-gray-700 py-1 px-1 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="task" name="task_id">
                            @foreach($tasks as $task)
                                <option value="{{$task->id}}">{{$task->neventame}}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full px-3 mb-6">
            <label class="block text-gray-700 text-sm" for="task">Notes</label>
            <textarea class="w-full focus:outline-none focus:shadow-outline border" id="note" rows="3" name="note"></textarea>
        </div>


        <div class="w-full px-3 mb-6 ">
            <div>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                    Save
                </button>
            </div>
        </div>


    </form>


</div>
