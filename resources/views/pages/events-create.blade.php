@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Create Event</h5>
					</div>
					<div class="ibox-content">
						<form class="form-horizontal" method="post" action="/events/create">
							{{ csrf_field() }}

							<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">Event Name</label>
								<div class="col-lg-8"><input type="text" placeholder="Event Name" name="name" class="form-control" value="{{ old('name') }}">
									@if ($errors->has('name'))
										<span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('camms_id') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">CAMMS ID</label>
								<div class="col-lg-8"><input type="text" placeholder="CAMMS ID" name="camms_id" class="form-control" value="{{ old('camms_id') }}">
									@if ($errors->has('camms_id'))
										<span class="help-block"><strong>{{ $errors->first('camms_id') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('type') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">Event Type</label>
								<div class="col-lg-8">
									<select class='form-control' placeholder='Event Type' name="type">
										<option value="">Please select</option>
										<option value="National" {{ old('type') ==1 ? 'selected' :'' }}>National</option>
										<option value="State" {{ old('type') ==2 ? 'selected' :'' }}>State</option>
										<option value="Multiclub" {{ old('type') ==3 ? 'selected' :'' }}>Multiclub</option>
										<option value="Club" {{ old('type') ==4 ? 'selected' :'' }}>Club</option>
									</select>
								</div>
							</div>
							<div class="form-group {{ $errors->has('style') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">Style of Event</label>
								<div class="col-lg-8">
									<select class='form-control' placeholder='Style of Event' name="style">
										<option value="">Please select</option>
										<option value="Pace Noted with Compulsory Reconnaissance" {{ old('style') == 'Pace Noted with Compulsory Reconnaissance' ? 'selected' :'' }}>Pace Noted with Compulsory Reconnaissance</option>
										<option value="Pace Noted with Optional Reconnaissance" {{ old('style') == 'Pace Noted with Optional Reconnaissance' ? 'selected' :'' }}>Pace Noted with Optional Reconnaissance</option>
										<option value="Blind (No Reconnaissance)" {{ old('style') =='Blind (No Reconnaissance)' ? 'selected' :'' }}>Blind (No Reconnaissance)</option>
										<option value="Navigation" {{ old('style') == 'Navigation' ? 'selected' :'' }}>Navigation</option>
									</select>
								</div>
							</div>
							<div class="form-group {{ $errors->has('competition_service') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">Competition Service</label>
								<div class="col-lg-8">
									<select class='form-control' placeholder='Competition Service' name="competition_service">
										<option value="">Please select</option>
										<option value="Gravel" {{ old('competition_service') == 'Gravel' ? 'selected' :'' }}>Gravel</option>
										<option value="Tarmac" {{ old('competition_service') == 'Tarmac' ? 'selected' :'' }}>Tarmac</option>
									</select>
								</div>
							</div>
							<div class="form-group {{ $errors->has('competitor_progression') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">How is the competitor progression through each stage monitored?</label>
								<div class="col-lg-8">
									<select class='form-control' placeholder='' name="competitor_progression">
										<option value="">Please select</option>
										<option value="Only at start and finish" {{ old('competitor_progression') ==1 ? 'selected' :'' }}>Only at start and finish</option>
										<option value="Start and finish plus radio points in stage - radio points report only when car is noticed missing" {{ old('competitor_progression') ==2 ? 'selected' :'' }}>Start and finish plus radio points in stage - radio points report only when car is noticed missing</option>
										<option value="Start and finish plus radio points in stage - radio points report all cars passing point" {{ old('competitor_progression') ==3 ? 'selected' :'' }}>Start and finish plus radio points in stage - radio points report all cars passing point</option>
										<option value="GPS tracked while on stage" {{ old('competitor_progression') ==4 ? 'selected' :'' }}>GPS tracked while on stage</option>
										<option value="Other" {{ old('competitor_progression') ==5 ? 'selected' :'' }}>Other</option>
									</select>

								</div>
								<div class="col-lg-8 col-lg-offset-4">
									<input type="text" id="competitor_progression_other" name="competitor_progression_other" class="form-control" value="{{ old('competitor_progression') }}" placeholder="Other">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">Medical services at park</label>
								<div class="col-lg-4">
									<label class="checkbox-inline"><input type="checkbox" name="medical_park_firstaid" class="medical_park" value="1">First Aid</label>
									<label class="checkbox-inline"><input type="checkbox" name="medical_park_ambulance" class="medical_park" value="1">Ambulance</label>
									<label class="checkbox-inline"><input type="checkbox" name="medical_park" id="medical_park_other" class="medical_park" value="1">Other</label>
								</div>
								<div class="col-lg-4"><input type="text" placeholder="Other" name="medical_park_other" class="form-control"></div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">Medical services on competition route</label>
								<div class="col-lg-4">
									<label class="checkbox-inline"><input type="checkbox" name="medical_route_firstaid" value="1">First Aid</label>
									<label class="checkbox-inline"><input type="checkbox" name="medical_route_ambulance" value="1">Ambulance</label>
									<label class="checkbox-inline"><input type="checkbox" name="medical_route" id="medical_route_other" value="1">Other</label>
								</div>
								<div class="col-lg-4"><input type="text" placeholder="Other" name="medical_route_other" class="form-control"></div>
							</div>
							<div class="form-group {{ $errors->has('spectator') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">Does event have dedicated spectator areas along route supervised by officials?</label>
								<div class="col-lg-8">
									<label class="radio-inline"><input type="radio" name="spectator" value="Yes">Yes</label>
									<label class="radio-inline"><input type="radio" name="spectator" value="No">No</label>
								</div>
							</div>
							<div class="form-group {{ $errors->has('competitive_distance') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">Competitive Distance?</label>
								<div class="col-lg-8">
									<input type="text" class="form-control" placeholder="Competitive Distance" name="competitive_distance" value="{{ old('competitive_distance') }}">
									@if ($errors->has('competitive_distance'))
										<span class="help-block"><strong>{{ $errors->first('competitive_distance') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('competitor_number') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">Number of competitors starting event?</label>
								<div class="col-lg-8">
									<input type="text" class="form-control" placeholder="Number of competitors starting event?" name="competitor_number" value="{{ old('competitor_number') }}">
									@if ($errors->has('competitor_number'))
										<span class="help-block"><strong>{{ $errors->first('competitor_number') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('location') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">Location</label>
								<div class="col-lg-8">
									<input id="location" type="text" class="form-control" placeholder="Location" name="location" value="{{ old('location') }}">
									<input id="latitude" type="hidden" name="latitude" value="{{ old('latitude') }}">
									<input id="longitude" type="hidden" name="longitude" value="{{ old('longitude') }}">
									@if ($errors->has('location'))
										<span class="help-block"><strong>{{ $errors->first('location') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">Description</label>
								<div class="col-lg-8">
									<textarea name="description" class="form-control" rows="4"  value="{{ old('description') }}"></textarea>
									@if ($errors->has('description'))
										<span class="help-block"><strong>{{ $errors->first('description') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('start_date') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">Start Date</label>
								<div class="col-lg-8">
									<input type="text" class="form-control date-input" placeholder="Start Date" name="start_date" value="{{ old('start_date') }}">
									@if ($errors->has('start_date'))
										<span class="help-block"><strong>{{ $errors->first('start_date') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('end_date') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">End Date</label>
								<div class="col-lg-8">
									<input type="text" class="form-control date-input" placeholder="End Date" name="end_date" id="date_of_birth" value="{{ old('end_date') }}">
									@if ($errors->has('end_date'))
										<span class="help-block"><strong>{{ $errors->first('end_date') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group{{ $errors->has('timezone') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">Timezone</label>
								<div class="col-lg-8">
									<select name="timezone" class="form-control">
										<option value="">Select timezone</option>
										<optgroup label="Australia">
											@foreach (\DateTimeZone::listIdentifiers(\DateTimeZone::AUSTRALIA) as $timezone)
												<option value="{{ $timezone }}">{{ preg_replace('%.*/%', '', str_replace('_', ' ', $timezone)) }} ({{ (new DateTime('now', new DateTimeZone($timezone)))->format('g:ia') }})</option>
											@endforeach
										</optgroup>
									</select>
									@if ($errors->has('timezone'))
										<span class="help-block">
											<strong>{{ $errors->first('timezone') }}</strong>
										</span>
									@endif
								</div>
							</div>
							<div class="form-group">
								<div class="col-lg-offset-4 col-lg-8">
									<button class="btn btn-sm btn-primary" type="submit">Create</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
	<script>
		$(document).ready(function(){

			$('input[name="medical_park_other"]').hide();
			$('input[name="medical_route_other"]').hide();
			$('input[name="competitor_progression_other"]').hide();

			$("#medical_park_other").on("click",function()
			{
				$('input[name="medical_park_other"]').toggle($(this).is(':checked'));
			});

			$("#medical_route_other").on("click",function()
			{
				$('input[name="medical_route_other"]').toggle($(this).is(':checked'));
			});

			$('select[name="competitor_progression"]').on('change', function() {
				if ($(this).val() == 'Other') {
					$('#competitor_progression_other').show();
				} else {
					$('#competitor_progression_other').hide();
				}
			});

			$('.date-input').datetimepicker({
				format : 'DD/MM/YYYY HH:mm',
				stepping : 30,
				useCurrent : false,
			});

			$("#location").on("focus", geolocate)
		});

		var autocomplete;

		function initAutocomplete() {
			autocomplete = new google.maps.places.Autocomplete((document.getElementById('location')), {types: ['geocode']});
			autocomplete.addListener('place_changed', fillInAddress);
		}

		function fillInAddress() {
			var place = autocomplete.getPlace();
			$("#latitude").val(place.geometry.location.lat());
			$("#longitude").val(place.geometry.location.lng());
		}

		function geolocate() {
			if (navigator.geolocation) {
				navigator.geolocation.getCurrentPosition(function(position) {
					var geolocation = {
						lat: position.coords.latitude,
						lng: position.coords.longitude
					};
					var circle = new google.maps.Circle({
						center: geolocation,
						radius: position.coords.accuracy
					});
					autocomplete.setBounds(circle.getBounds());
				});
			}
		}
	</script>
	<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBA49o4nTKm6sEzirzmdhv_aQcGWQ7k0-I&libraries=places&callback=initAutocomplete"></script>
@endsection
