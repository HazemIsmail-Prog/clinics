
    <div class="row">
        <div class="bg-light border-primary border" wire:loading style="position: fixed;top: 70px;right: 10px;padding: 10px 20px;border-radius: 10px;z-index: 1000;">
            <h6 class="m-0 font-weight-bold text-primary"><div class="spinner-border small"></div> Loading ...</h6>
        </div>
        <div class="col-md-3 align-self-end mb-2">
            <div class="form-group mb-0">
{{--                <label for="date" class="text-primary">Date</label>--}}
                <input wire:model="date" type="date" name="date" id="date" class="form-control mb-0">
            </div>
        </div>
        <div class="col-md-3 align-self-end mb-2">

            <div class="form-group mb-0">
{{--                <label for="app_department" class="text-primary d-block">Department</label>--}}
                <select wire:model="app_department" class="custom-select form-control mb-0" name="app_department"
                        id="app_department">
                    @foreach($departments as $department)
                        <option
                            value="{{$department->id}}">{{$department->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6 text-left align-self-end mb-2">
            <a wire:click="show_cancelled"
               class="btn btn-sm btn-primary">{{$show_cancelled ? 'Hide Cancelled' : 'Show Cancelled'}}
            </a>

            <a href="{{route('appointments.print',['date' => $date,'department'=>$app_department])}}"
               class="btn btn-sm btn-primary" target="_blank">
                <i class="fa fa-print"></i> Print
            </a>


            <a href="{{route('appointments.search')}}"
               class="btn btn-sm btn-primary" target="_blank">
                <i class="fa fa-search"></i>
                Search
            </a>

            <a href="{{route('appointments.ministry_book',['date'=>$date])}}"
               class="btn btn-sm btn-primary" target="_blank">Ministry Book
            </a>
        </div>


    </div>




<div style="max-height:calc(100vh - 160px);overflow-y: scroll;">
    <div id='calendar'></div>
</div>




@section('scripts')

    <script src='{{ asset('assets\plugins\fullcalendar\main.min.js') }}'></script>

    <script>

        document.addEventListener('livewire:load', function () {

            resources = {!! json_encode($resources) !!};
            events = {!! json_encode($events) !!};
            date = {!! json_encode($date) !!}
            loadCalendar(resources, events, date);
        });



        setInterval(function () {
        @this.update_view_data();
        }, 30000);



        window.addEventListener('alert', event => {
            alert(event.detail.message);
        });

        window.addEventListener('data-updated', event => {

            resources = event.detail.resources;
            events = event.detail.events;
            date = event.detail.date
            loadCalendar(resources, events, date);
        });


        function loadCalendar(resources, events, date) {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'title',
                    center: '',
                    right: 'prev,next today'
                },
                titleFormat: { // will produce something like "Tuesday, September 18, 2018"
                    month: 'long',
                    year: 'numeric',
                    day: 'numeric',
                    weekday: 'long'
                },
                initialDate: date,
                themeSystem: 'pulse',
                displayEventTime: false,
                timeZone: 'Asia/Baghdad',
                initialView: 'resourceTimeGridDay',
                slotDuration: '00:15',
                // slotMinTime: '08:00',
                // slotMaxTime: '21:00',
                slotMinTime: '{{auth()->user()->clinic->working_start}}',
                slotMaxTime: '{{auth()->user()->clinic->working_end}}',
                // scrollTime: '13:00:00',
                height: 'auto',
                handleWindowResize: true,
                eventOverlap: false, // will cause the event to take up entire resource height
                nowIndicator: true,
                allDaySlot: false,
                selectMirror: true,
                eventTimeFormat: {
                    hour: 'numeric',
                    minute: '2-digit',
                    meridiem: 'short'
                },
                resourceOrder: 'sort', // when title tied, order by id
                resources: resources,
                events: events,



                @can('Appointments_Update')
                eventChange: function (arg) {
                    if (confirm('Edit selected appointment.\nAre you sure ?')) {
                        var id = arg.event.id;
                        var startTime = arg.event.startStr.substr(11);
                        var endTime = arg.event.endStr.substr(11);
                        var device = parseInt(arg.event._def.resourceIds);

                    @this.quick_edit(id, startTime, endTime, device);
                    } else {
                    @this.update_view_data();
                    }
                },


                eventClick: function (arg) {

                    var id = arg.event.id;

                @this.edit_appointment(id);

                },
                editable: true,
                @else
                editable: false,
                @endcan

                    @can('Appointments_Create')
                select: function (arg) {

                    var resource = arg.resource.id;
                    var device_name = arg.resource.title;
                    var day = arg.startStr.substr(8, 2);
                    var month = arg.startStr.substr(5, 2);
                    var year = arg.startStr.substr(0, 4);
                    var fulldate = year + '-' + month + '-' + day
                    var startTime = arg.startStr.substr(11);
                    var endTime = arg.endStr.substr(11);

                @this.create_appointment(resource, startTime, endTime);

                },
                selectable: true,
                @else
                selectable: false,
                @endcan


            });



            calendar.render();
            $('.fc-toolbar-chunk').addClass('text-primary');


        }


    </script>

@endsection
