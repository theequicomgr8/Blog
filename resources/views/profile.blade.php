<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>User : Profile</title>
	<link href="{{ asset('css/app.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/bootstrap.min.css')}}" rel="stylesheet" />
    <script src="{{ asset('js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{ asset('js/jquery.min.js')}}"></script>
</head>
<body>
<div class="container">
	<div class="row mt-4">
		<div class="col-xl-10">
			<h4>User Dashboard</h4>
		</div>
		<div class="col-xl-2">
			<a href="{{Route('logout')}}" class="btn btn-info">Logout</a>
		</div>
	</div>

	@if(Session::has('success'))
	<div class="alert alert-success">
	  <strong>Success!</strong> {{Session('success')}}
	</div>
	@endif
	<div class="row">
		<form method="post" action="{{Route('student.save')}}" enctype="multipart/form-data" class="form-inline">
			@csrf
			<div class="col-xl-3">
				<div class="form-group">
					<label>Name : </label>
					<input type="text" name="name" class="form-control form-control-sm" value="{{@$studentdata->sname ?? ''}}">
					@error('name')
					<span class="text-danger">{{$message}}</span>
					@enderror
				</div>
			</div>
			<div class="col-xl-3">
				<div class="form-group">
					<label>Email : </label>
					<input type="text" name="email" class="form-control form-control-sm" value="{{Session('user')}}" readonly>
				</div>
				@error('email')
					<span class="text-danger">{{$message}}</span>
				@enderror
			</div>
			<div class="col-xl-3">
				<div class="form-group">
					<label>Country : </label>
					<select class="form-select form-select-sm" name="country" id="country">
						<option value="">Select Country :</option>
						@foreach($countries as $key => $country)
						<option value="{{$country->id}}" {{@$country->id ==@$studentdata->country_id ? 'selected' : '' }} >{{$country->name}}</option>
						@endforeach
					</select>
				</div>
				@error('country')
					<span class="text-danger">{{$message}}</span>
				@enderror
			</div>
			<div class="col-xl-3">
				<div class="form-group">
					<label>State : </label>
					<select class="form-select form-select-sm" name="state" id="state">
						<option>Select State</option>
						@if(!empty($studentdata->state_id))
						@foreach($statefetch as $sfetch)
						<option value="{{$sfetch->id}}" {{ @$sfetch->id==$studentdata->state_id ? 'selected' : '' }}>{{$sfetch->name}}</option>
						@endforeach
						@endif
					</select>
				</div>
				@error('state')
					<span class="text-danger">{{$message}}</span>
				@enderror
			</div>
			<div class="col-xl-3">
				<div class="form-group">
					<label>City : </label>
					<select class="form-select form-select-sm" name="city" id="city">
						<option>Select City</option>
						@if(!empty($studentdata->city_id))
						@foreach($cityfetch as $cfetch)
						<option value="{{$cfetch->id}}" {{ @$cfetch->id==$studentdata->city_id ? 'selected' : '' }}>{{$cfetch->name}}</option>
						@endforeach
						@endif
					</select>
				</div>
				@error('city')
					<span class="text-danger">{{$message}}</span>
				@enderror
			</div><br>

			<div class="col-xl-3" id="myid">
				@foreach($mobiles as $key => $mob)
				<div class="col-xl-11" style="float: left;">
					<div class="form-group">
						<label>Mobile : </label>
						<input type="text" name="mobile[]" class="form-control form-control-sm" value="{{ $mob->mobile }}">
					</div>
				</div>
				@endforeach
				<div class="col-xl-1" style="float: right;"><br>
					<button class="btn btn-info" id="add">Add</button>
				</div>
			</div>

			<div class="col-xl-3">
				<input type="submit" value="Save" class="btn btn-info mt-4">
			</div>
			<!-- <input type="submit" value="Save" class="btn btn-info"> -->
		</form>
	</div>
</div>

<script src="{{ asset('js/jquery.min.js')}}"></script>
<script type="text/javascript">
		// var country=$('input[name="name"]').val();

	$(document).ready(function(){
		$(document).on("change",$('select[name="country"]'),function(){
			var countryID=$('select[name="country"]').val();
			$.ajax({
				url : '/getState',
				type: 'GET',
				data: { countryid:countryID },
				success:function(data)
				{
					$("#state").append(data);
				}
			});
		});


		//get city
		$(document).on("change",$('select[name="state"]'),function(){
			var stateID=$('select[name="state"]').val();
			$.ajax({
				url : '/getCity',
				type: 'GET',
				data: { stateid:stateID },
				success:function(data)
				{
					$("#city").append(data);
				}
			});
		});


		var i=1;
		$(document).on("click","#add",function(e){
			e.preventDefault();
			i++;
			var html='<div class="col-xl-12" id="row'+i+'"><div class="form-group"><label>Mobile : </label><input type="text" name="mobile[]" class="form-control form-control-sm"></div><div class="col-xl-1" style="float: right; margin-left:41px; margin-top:-38px;"><button class="btn btn-danger remove" data-eid="'+i+'">X</button></div></div>';
				$("#myid").append(html);
		});

		$(document).on("click",".remove",function(e){
			e.preventDefault();
			var id=$(this).attr('data-eid');
			$("#row"+id).remove();
		});
	});
</script>
</body>
</html>