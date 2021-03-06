@extends('layouts.dashboard')

@section('content')
	<div class="dash__block">
		<h1 class="dash__header">Create Activity</h1>
		<h4 class="dash__description">Add a new employee to the system.</h4>
		@include('shared.session_message')
		@include('shared.error_message')
		<form class="request" method="POST" action="/admin/activity">
			{{ csrf_field() }}
			<div class="form-group">
				<label for="activity_name">Name <span class="request__validate">(e.g. Haircut, Coloring)</span></label>
				<input name="name" type="text" id="activity_name" class="form-control request__input" placeholder="Name" value="{{ old('name') }}" autofocus>
			</div>
			<div class="form-group">
				<label for="activity_description">Description <span class="request__validate">(optional)</span></label>
				<input name="description" type="text" id="activity_description" class="form-control request__input" placeholder="Description" value="{{ old('description') }}" autofocus>
			</div>
			<div class="form-group">
				<label for="activity_duration">Duration <span class="request__validate">(24 hour format)</span></label>
				<input name="duration" type="text" id="activity_duration" class="form-control request__input" placeholder="hh:mm" value="{{ old('duration') }}" masked-time>
			</div>
			<button class="btn btn-lg btn-primary btn-block btn--margin-top">Create Activity</button>
		</form>
	</div>
	<hr>
	<div class="dash__block">
		<h1 class="dash__header dash__header--margin-top">Activities</h1>
		<h4 class="dash__description">A table of all activities within the business.</h4>
		@if ($activities->count())
			<table class="table no-margin calender">
		        <tr>
		        	<th class="table__id table__right-solid">ID</th>
					<th class="table__name">Name</th>
					<th class="table__text">Description</th>
					<th class="table__time">Duration</th>
				</tr>
				@foreach ($activities as $activity)
					<tr>
						<td class="table__id table__right-solid">{{ $activity->id }}</td>
						<td class="table__name table__right-dotted">{{ $activity->name }}</td>
						<td class="table__text table__right-dotted">{{ $activity->description }}</td>
						<td class="table__time">{{ $activity->duration }}</td>
					</tr>
				@endforeach
		    </table>
	    @else
			@include('shared.error_message_thumbs_down', [
				'message' => 'No activities found.',
				'subMessage' => 'Try add an activity using the form above.'
			])
		@endif
	</div>
@endsection