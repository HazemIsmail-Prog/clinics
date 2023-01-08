<div>
    <div class="bg-light border-primary border" wire:loading style="position: fixed;top: 70px;right: 10px;padding: 10px 20px;border-radius: 10px;z-index: 1000;">
        <h6 class="m-0 font-weight-bold text-primary"><div class="spinner-border small"></div> Loading ...</h6>
    </div>
    @foreach($app_departments as $department)

        <div class="department">


            <div class="table-responsive">
                <table class="table table-sm table-borderless table-hover table-striped">
                    <thead>
                    <tr class="bg-primary text-white">
                        <th colspan="3">{{$department->name}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($department->app_devices->count()>0)
                        @foreach($department->app_devices as $device)
                            <tr data-department = "{{$device->app_department_id}}" data-device="{{$device->id}}">
                                <td>{{$device->name}}</td>
                                <td class="text-center">
                                    @if($device->active == 1)
                                        <span class="badge badge-success badge-pill">Active</span>
                                    @else
                                        <span class="badge badge-danger badge-pill">Inactive</span>
                                    @endif
                                </td>



                                <td class="text-right">

                                    <a href="{{route('app_devices.edit',$device->id)}}"
                                       class="btn btn-outline-info btn-sm" title="Edit"><i
                                            class="fa fa-edit"></i></a>
                                    @if($device->appointments_count == 0)
                                        <a
                                            class="btn btn-outline-danger btn-sm"
                                            href="{{route('app_devices.destroy',$device->id)}}"
                                            onclick="event.preventDefault();confirm('You\'r About to Delete This Device\nARE YOU SURE???') ? document.getElementById('delete-form-{{ $device->id }}').submit() : false;"
                                        >
                                            <i class="fa fa-trash"></i>
                                        </a>
                                        <form
                                            action="{{route('app_devices.destroy',$device->id)}}"
                                            id="delete-form-{{$device->id}}"
                                            method="POST"
                                            style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    @else
                                        <button
                                            disabled="true"
                                            title="There are related records for this Department and Cannot be Deleted"
                                            class="btn btn-outline-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="3">
                                No Records Found
                            </td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>


    @endforeach

</div>


@section('scripts')

    <script src="{{asset('assets\plugins\jquery_ui\jquery-ui.min.js')}}"></script>

    <script>

        $(document).ready(function () {

            $('table tbody').sortable({
                update: function (event, ui) {
                    var positions = [];
                    $(this).children().each(function (index){
                        positions.push([index+1 ,$(this).attr('data-device'), $(this).attr('data-department')]);


                    });
                    @this.sort(positions);



                    // var positions = $(this).children(index);
                    // console.log(positions)
                    // @this.sort(index);
                }
        });
            //         $(this).children().each(function (index) {
            //         @this.sort(index);
            //
            //         })
            //     }
            // });

        });

    </script>

@endsection
