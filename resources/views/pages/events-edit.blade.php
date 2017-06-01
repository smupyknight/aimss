@extends('layouts.default')

@section('content')
	<div class="wrapper wrapper-content  animated fadeInRight">
		<div class="row">
			<div class="col-lg-12">
				<div class="ibox float-e-margins">
					<div class="ibox-title">
						<h5>Edit Event</h5>
					</div>
					<div class="ibox-content">
						<form class="form-horizontal" method="post" action="/events/edit/{{ $event->id }}">
							{{ csrf_field() }}

							<div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">Event Name</label>
								<div class="col-lg-8"><input type="text" placeholder="Event Name" name="name" class="form-control" value="{{ old('name',$event->name) }}">
									@if ($errors->has('name'))
										<span class="help-block"><strong>{{ $errors->first('name') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('camms_id') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">CAMMS ID</label>
								<div class="col-lg-8"><input type="text" placeholder="CAMMS ID" name="camms_id" class="form-control" value="{{ old('camms_id', $event->camms_id) }}">
									@if ($errors->has('camms_id'))
										<span class="help-block"><strong>{{ $errors->first('camms_id') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">Status</label>
								<div class="col-lg-8">
									<div class="radio">
										<label><input type="radio" name="status" value="pending"{{ old('status', $event->status) == 'pending' ? ' checked' : '' }}> Pending</label>
										<label><input type="radio" name="status" value="accepted"{{ old('status', $event->status) == 'accepted' ? ' checked' : '' }}> Accepted</label>
									</div>
									@if ($errors->has('status'))
										<span class="help-block"><strong>{{ $errors->first('status') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">Event Type</label>
								<div class="col-lg-8">
									<select class='form-control' placeholder='Event Type' name="type" id="type">
										<option value="National" {{ old('type',$event->type) == 'National'? 'selected' : '' }}>National</option>
										<option value="State" {{ old('type',$event->type) == 'State'? 'selected' : '' }}>State</option>
										<option value="Multiclub" {{ old('type',$event->type) == 'Multiclub'? 'selected' : '' }}>Multiclub</option>
										<option value="Club" {{ old('type',$event->type) == 'Club'? 'selected' : '' }}>Club</option>
									</select>
									@if ($errors->has('type'))
										<span class="help-block"><strong>{{ $errors->first('type') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group{{ $errors->has('style') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">Style of Event</label>
								<div class="col-lg-8">
									<select class='form-control' placeholder='Style of Event' name="style" id="style">
										<option value="Pace Noted with Compulsory Reconnaissance" {{ old('style',$event->style) == 'Pace Noted with Compulsory Reconnaissance' ? 'selected' :'' }}>Pace Noted with Compulsory Reconnaissance</option>
										<option value="Pace Noted with Optional Reconnaissance" {{ old('style',$event->style) == 'Pace Noted with Optional Reconnaissance' ? 'selected' :'' }}>Pace Noted with Optional Reconnaissance</option>
										<option value="Blind (No Reconnaissance)" {{ old('style',$event->style) == 'Blind (No Reconnaissance)' ? 'selected' :'' }}>Blind No Reconnaissance</option>
										<option value="Navigation" {{ old('style',$event->style) == 'Navigation' ? 'selected' :'' }}>Navigation</option>
									</select>
									@if ($errors->has('style'))
										<span class="help-block"><strong>{{ $errors->first('style') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group{{ $errors->has('competition_service') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">Competition Service</label>
								<div class="col-lg-8">
									<select class='form-control' placeholder='Competition Service' name="competition_service" id="competition_service">
										<option value="Gravel" {{ old('competition_service',$event->competition_service) == 'Gravel' ? 'selected' : '' }}>Gravel</option>
										<option value="Tarmac" {{ old('competition_service',$event->competition_service) == 'Tarmac' ? 'selected' : '' }}>Tarmac</option>
									</select>
									@if ($errors->has('competition_service'))
										<span class="help-block"><strong>{{ $errors->first('competition_service') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group{{ $errors->has('competitor_progression') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">How is the competitor progression through each stage monitored?</label>
								<div class="col-lg-8">
									<select class='form-control' placeholder='Competitor Progression' name="competitor_progression" id="competitor_progression">
										<option value="Only at start and finish" {{ old('competitor_progression',$event->competitor_progression) == 'Only at start and finish'? 'selected' : '' }}>Only at start and finish</option>
										<option value="Start and finish plus radio points in stage - radio points report only when car is noticed missing" {{ old('competitor_progression',$event->competitor_progression) == 'Start and finish plus radio points in stage - radio points report only when car is noticed missing'? 'selected' : '' }}>Start and finish plus radio points in stage - radio points report only when car is noticed missing</option>
										<option value="Start and finish plus radio points in stage - radio points report all cars passing point" {{ old('competitor_progression',$event->competitor_progression) == 'Start and finish plus radio points in stage - radio points report all cars passing point'? 'selected' : '' }}>Start and finish plus radio points in stage - radio points report all cars passing point</option>
										<option value="GPS tracked while on stage" {{ old('competitor_progression',$event->competitor_progression) == 'GPS tracked while on stage'? 'selected' : '' }}>GPS tracked while on stage</option>
										<option value="Other" {{ old('competitor_progression',$event->competitor_progression) == 'Other'? 'selected' : '' }}>Other</option>
									</select>
								</div>
								<div class="col-lg-8 col-lg-offset-4">
									<input type="text" name="competitor_progression_other" id="competitor_progression_other" class="form-control" value="{{ $event->competitor_progression_other }}" placeholder="Other">
								</div>
								@if ($errors->has('competitor_progression'))
									<span class="help-block"><strong>{{ $errors->first('competitor_progression') }}</strong></span>
								@endif
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">Medical services at park</label>
								<div class="col-lg-4">
									<label class="checkbox-inline"><input type="checkbox" name="medical_park_firstaid" value="1" {{ old('medical_park_firstaid',$event->medical_park_firstaid) == 1 ? 'checked': '' }}>First Aid</label>
									<label class="checkbox-inline"><input type="checkbox" name="medical_park_ambulance" value="1" {{ old('medical_park_ambulance',$event->medical_park_ambulance) == 1 ? 'checked': '' }}>Ambulance</label>
									<label class="checkbox-inline"><input type="checkbox" name="medical_park" id="medical_park_other" value="1" {{ old('medical_park_other',$event->medical_park_other) ? 'checked': '' }}>Other</label>

								</div>
								<div class="col-lg-4"><input type="text" placeholder="Other" name="medical_park_other" value="{{ $event->medical_park_other }}" class="form-control"></div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">Medical services on competition route</label>
								<div class="col-lg-4">
									<label class="checkbox-inline"><input type="checkbox" name="medical_route_firstaid" value="1" {{ old('medical_route_firstaid',$event->medical_route_firstaid) == 1 ? 'checked': '' }}>First Aid</label>
									<label class="checkbox-inline"><input type="checkbox" name="medical_route_ambulance" value="1" {{ old('medical_route_ambulance',$event->medical_route_ambulance) == 1 ? 'checked': '' }}>Ambulance</label>
									<label class="checkbox-inline"><input type="checkbox" name="medical_route" id="medical_route_other" value="1" {{ old('medical_route',$event->medical_route_other) ? 'checked': '' }}>Other</label>

								</div>
								<div class="col-lg-4"><input type="text" placeholder="Other" name="medical_route_other" value="{{ $event->medical_route_other }}" class="form-control"></div>
							</div>
							<div class="form-group">
								<label class="col-lg-4 control-label">Does event have dedicated spectator areas along route supervised by officials?</label>
								<div class="col-lg-8">
									@foreach (['Yes','No'] as $spectator)
										<label class="radio-inline"><input type="radio" name="spectator" value="{{ $spectator }}"{{ old('spectator',$event->spectator) == $spectator ? 'checked': '' }}>{{ $spectator }}</label>
									@endforeach
								</div>
							</div>
							<div class="form-group {{ $errors->has('competitive_distance') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">Competitive Distance?</label>
								<div class="col-lg-8"><input type="text" placeholder="Competitive Distance?" name="competitive_distance" class="form-control" value="{{ old('competitive_distance', $event->competitive_distance) }}">
									@if ($errors->has('competitive_distance'))
										<span class="help-block"><strong>{{ $errors->first('competitive_distance') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('competitor_number') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">Number of competitors starting event?</label>
								<div class="col-lg-8"><input type="text" placeholder="Number of competitors starting event?" name="competitor_number" class="form-control" value="{{ old('competitor_number', $event->competitor_number) }}">
									@if ($errors->has('competitor_number'))
										<span class="help-block"><strong>{{ $errors->first('competitor_number') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('location') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">Location</label>
								<div class="col-lg-8">
									<input type="text" class="form-control" placeholder="Location" name="location" id="location" value="{{ old('location',$event->location) }}">
									<input id="latitude" type="hidden" name="latitude" value="{{ old('latitude',$event->latitude) }}">
									<input id="longitude" type="hidden" name="longitude" value="{{ old('longitude',$event->longitude) }}">
									@if ($errors->has('location'))
										<span class="help-block"><strong>{{ $errors->first('location') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('description') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">Description</label>
								<div class="col-lg-8">
									<textarea name="description" class="form-control" rows="4" >{{ old('description',$event->description) }}</textarea>
									@if ($errors->has('description'))
										<span class="help-block"><strong>{{ $errors->first('description') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('start_date') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">Start Date</label>
								<div class="col-lg-8">
									<input type="text" class="form-control date-input" placeholder="Start Date" name="start_date"  value="{{ old('start_date', $event->start_date->setTimezone($event->timezone)->format('d/m/Y H:i:s')) }}">
									@if ($errors->has('start_date'))
										<span class="help-block"><strong>{{ $errors->first('start_date') }}</strong></span>
									@endif
								</div>
							</div>
							<div class="form-group {{ $errors->has('end_date') ? ' has-error' : '' }}">
								<label class="col-lg-4 control-label">End Date</label>
								<div class="col-lg-8">
									<input type="text" class="form-control date-input" placeholder="End Date" name="end_date" value="{{ old('end_date', $event->end_date->setTimezone($event->timezone)->format('d/m/Y H:i:s')) }}">
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
												<option value="{{ $timezone }}" {{ old('timezone',$event->timezone)==$timezone ? 'selected' : '' }}>{{ preg_replace('%.*/%', '', str_replace('_', ' ', $timezone)) }} ({{ (new DateTime('now', new DateTimeZone($timezone)))->format('g:ia') }})</option>
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
									<button class="btn btn-sm btn-primary" type="submit">Update</button>
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

			if(!$("#medical_park_other").is(':checked'))
				$('input[name="medical_park_other"]').hide();

			if(!$("#medical_route_other").is(':checked'))
				$('input[name="medical_route_other"]').hide();

			if($('select[name="competitor_progression"]').val() != 'Other')
				$('#competitor_progression_other').hide();

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
