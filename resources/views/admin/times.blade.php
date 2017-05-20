@extends('layouts.dashboard')

@section('content')
    <div class="dash__block">
        <h1 class="dash__header">Business Times</h1>
        <h4 class="dash__description">Add a new business time for the week. There must be only one time per day.</h4>
        @if ($flash = session('message'))
            <div class="alert alert-success">
                {{ $flash }}
            </div>
        @endif
        @if (count($errors))
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif
        <form class="request" method="POST" action="/admin/times">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="times_day">Day <span class="request__validate">(select a day within the week)</span></label>
                <select name="day" id="times_day" class="form-control request__input">
                    @foreach (getDaysOfWeek() as $day)
                        <option value="{{ strtoupper($day) }}" {{ old('day') == strtoupper($day) ? 'selected' : null }}>{{ $day }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group request__flex-container">
                <div class="request__flex request__flex--left">
                    <label for="times_start_time">Start Time <span class="request__validate">(24 hour format e.g. 17:00 = 05:00 PM)</span></label>
                    <input name="start_time" type="time" id="times_start_time" class="form-control request__input" value="{{ old('start_time') ? old('start_time') : '09:00' }}" autofocus>
                </div>
                <div class="request__flex request__flex--right">
                    <label for="times_end_time">End Time</label>
                    <input name="end_time" type="time" id="times_end_time" class="form-control request__input" value="{{ old('end_time') ? old('end_time') : '17:00' }}" autofocus>
                </div>
            </div>
            <button class="btn btn-lg btn-primary btn-block btn--margin-top">Create Business Time</button>
        </form>
    </div>
    <hr>
    <div class="dash__block">
        <h1 class="dash__header dash__header--margin-top">Open Business Times</h1>
        <h4 class="dash__description">A table of all activities within the business.</h4>
        <table class="table no-margin calender">
            <tr>
                @foreach (getDaysOfWeek() as $day)
                    <th class="table__day">{{ $day }}</th>
                @endforeach
            </tr>
            <tr>
                @foreach (getDaysOfWeek(true) as $day)
                    <td class="table__day table__right-dotted">
                        @if ($time = $times->where('day', $day)->first())
                            <div class="item">
                                <section class="item__block">
                                    <div class="text-center">{{ toTime($time->start_time, false) }} - {{ toTime($time->end_time, false) }}</div>
                                </section>
                            </div>
                        @else
                            <div class="table__message">N/A</div>
                        @endif
                    </td>
                @endforeach
            </tr>
        </table>
    </div>
@endsection