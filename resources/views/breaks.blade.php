@extends('frontLayout.app1')
@section('title')
Breaks
@stop

@section('style')


@stop

@section('content')
<div class="container" >
    <form>
    <div class='row'>
        <div class="col-lg-4">
            <input class='form-control' type='date' name='date' value='{{$date}}' required>
        </div>
        <div class="col-lg-4">
            <button type='submit' class='btn btn-info'>Search</button>
        </div>
    </div>
    </form>
    <hr>
    <div class='row'>
        <div class="col-lg-12">
            <table class="table table-striped table-bordered breaks" style="width:100%" id='breaks'>
                <thead style='font-color:black;'>
                <tr style='color:black;'>
                    <th style='color:black;'>Name</th>
                    <th style='color:black;' scope="col">Department</th>
                    <th style='color:black;' scope="col">Location</th>
                    <th style='color:black;' scope="col">Out</th>
                    <th style='color:black;' scope="col">In</th>
                    <th style='color:black;' scope="col">Difference</th>
                    <th style='color:black;' scope="col">Reason</th>
                    {{-- <th style='color:black;' scope="col">Action</th> --}}
                </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $attendance)
                    <tr>
                        <td>{{$attendance->employee->name}}</td>
                        <td>{{$attendance->employee->department}}</td>
                        <td>@if($attendance->location){{$attendance->location->location}}@endif</td>
                        <td>{{$attendance->break_out}}</td>
                        <td>{{$attendance->break_in}}</td>
                       @if($attendance->break_in != null)
                            @php
                                   $ob_start = new DateTime($attendance->break_out); 
                                        $ob_diff = $ob_start->diff(new DateTime($attendance->break_in));
                                        $work_diff_hours = round($ob_diff->s / 3600 + $ob_diff->i / 60 + $ob_diff->h + $ob_diff->days * 24, 2);
                            @endphp
                            <td @if($work_diff_hours > 1) class='bg-danger text-white' @endif >
                            {{$work_diff_hours*60}} Minutes
                            
                            </td>
                            @else
                            <td class='bg-warning'>
                            </td>
                            @endif
                        <td style='color:black;' scope="col">{{$attendance->reason}}</td>
                        {{-- <td style='color:black;' scope="col"> <button type='submit'  class="btn btn-primary" >Save</button> </td> --}}
                    </form>
                    </tr>
         
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection