@extends('_layouts.backend')
@section('title', __('exhibitor.add'))

@section('content')
<div class="block block-bordered">
	<form action="{{ route('admin.exhibitor.store') }}" method="post">
		{{ csrf_field() }}
		<div class="block-content block-content-full">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group{{ $errors->has('name') ? ' is-invalid' : '' }}">
						<label for="field-name">{{ __('exhibitor.name') }}</label>
						<input type="text" class="form-control" id="field-name" name="name" value="{{ old('name') }}">
						<div class="invalid-feedback">{{ $errors->first('name') }}</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group{{ $errors->has('email') ? ' is-invalid' : '' }}">
						<div class="form-group{{ $errors->has('email') ? ' is-invalid' : '' }}">
							<label for="field-email">{{ __('exhibitor.email') }}</label>
							<input type="text" class="form-control" id="field-email" name="email" value="{{ old('email') }}">
							<div class="invalid-feedback">{{ $errors->first('email') }}</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group{{ $errors->has('phone') ? ' is-invalid' : '' }}">
						<div class="form-group{{ $errors->has('phone') ? ' is-invalid' : '' }}">
							<label for="field-phone">{{ __('exhibitor.phone') }}</label>
							<input type="text" class="form-control" id="field-phone" name="phone" value="{{ old('phone') }}" placeholder="{{ __('exhibitor.phone_placeholder') }}">
							<div class="invalid-feedback">{{ $errors->first('phone') }}</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group{{ $errors->has('fax') ? ' is-invalid' : '' }}">
						<div class="form-group{{ $errors->has('fax') ? ' is-invalid' : '' }}">
							<label for="field-fax">{{ __('exhibitor.fax') }}</label>
							<input type="text" class="form-control" id="field-fax" name="fax" value="{{ old('fax') }}" placeholder="{{ __('exhibitor.phone_placeholder') }}">
							<div class="invalid-feedback">{{ $errors->first('fax') }}</div>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group{{ $errors->has('country_id') ? ' is-invalid' : '' }}">
						<label for="field-country_id">{{ __('general.country') }}</label>
						<select class="js-select2 form-control" id="field-country_id" name="country_id" style="width: 100%;" data-placeholder="{{ __('general.choose') }}">
							<option></option>
							@foreach($countries as $country)
							<option value="{{ $country->id }}" {{ old('country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
							@endforeach
						</select>
						<div class="invalid-feedback">{{ $errors->first('country_id') }}</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group{{ $errors->has('video') ? ' is-invalid' : '' }}">
						<div class="form-group{{ $errors->has('video') ? ' is-invalid' : '' }}">
							<label for="field-video">{{ __('exhibitor.video') }}</label>
							<input type="text" class="form-control" id="field-video" name="video" value="{{ old('video') }}">
							<div class="invalid-feedback">{{ $errors->first('video') }}</div>
						</div>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group{{ $errors->has('categories') ? ' is-invalid' : '' }}">
						<div class="form-group{{ $errors->has('categories') ? ' is-invalid' : '' }}">
							<label for="field-categories">{{ __('exhibitor.category') }}</label>
							<select class="form-control" id="field-categories" name="categories[]" style="width: 100%;" multiple="multiple">
								<option></option>
								@if($ca = old('categories'))
                                    @foreach($ca as $me)
                                    <option value="{{ $me }}" selected>
                                        @php
                                        $s = \App\ProductCategory::ancestorsAndSelf($me)->pluck('name');

                                        echo $s[0] . ' / ' . $s[1] . ' / ' . $s[2];
                                        @endphp
                                    </option>
                                    @endforeach
                                @endif
							</select>

							@push('script')
							<script type="text/javascript">
								$(function () {
									pageSize = 50
									items = [
									@foreach($categories as $c1)
									@foreach($c1->children as $c2)
									@foreach($c2->children as $c3)
									{
										id: {{ $c3->id }},
										text : "{!! $c3->name !!}"
									},
									@endforeach
									@endforeach
									@endforeach
									]

									$.fn.select2.amd.require(["select2/data/array", "select2/utils"], function (ArrayData, Utils) {
										function CustomData($element, options) {
											CustomData.__super__.constructor.call(this, $element, options);
										}

										Utils.Extend(CustomData, ArrayData);

										CustomData.prototype.query = function (params, callback) {
											params.page = params.page || 1;
											var data = {};
											data.results = items.slice((params.page - 1) * pageSize, params.page * pageSize);
											data.pagination = {};
											data.pagination.more = params.page * pageSize < items.length;
											callback(data, params);
										};

										$(document).ready(function () {
											var me = $("#field-categories").select2({
												ajax: {},
												multiple: true,
												dataAdapter: CustomData,
												placeholder: '{{ __('general.choose') }}',
											});
											me.on('select2:selecting', function(){
												if($("#field-categories").val().length>2){
													$("#field-categories").val($("#field-categories").val().slice(0,2));

													console.log($("#field-categories").val())
												}
											});
										});
									});
								});
							</script>
							@endpush
							<div class="invalid-feedback">{{ $errors->first('categories') }}</div>
						</div>
					</div>
				</div>
				<div class="col-md-12" style="position: relative;">
					<div class="form-group{{ $errors->has('address') ? ' is-invalid' : '' }}">
						<label for="field-address">{{ __('exhibitor.address') }}</label>
						<textarea id="field-address" class="form-control" name="address" rows="6">{{ old('address') }}</textarea>
						<div class="invalid-feedback">{{ $errors->first('address') }}</div>
					</div>
				</div>

				<div class="col-md-12" style="position: relative;">
					<div class="form-group{{ $errors->has('description') ? ' is-invalid' : '' }}">
						<label for="field-description">{{ __('exhibitor.description') }}</label>
						<textarea id="field-description" class="form-control" name="description" rows="6">{{ old('description') }}</textarea>
						<div class="invalid-feedback">{{ $errors->first('description') }}</div>
					</div>
				</div>

				<div class="col-md-12">
					<label>{{ __('general.exhibitions') }}</label>

					@foreach(range(1, 5) as $i)
					<div class="row">
						<div class="col-md-8 mb-20">
							<select class="js-select2 form-control" name="exhibitions[{{ $i }}][exhibition]" style="width: 100%;" data-placeholder="{{ __('general.choose') }}">
								<option></option>
								@foreach($exhibitions as $exhibition)
								<option value="{{ $exhibition->id }}">{{ $exhibition->title }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-4 mb-20">
							<input type="text" class="form-control" name="exhibitions[{{ $i }}][booth]" placeholder="Booth Number">
						</div>
					</div>
					@endforeach
				</div>
			</div>
		</div>
		<div class="block-content block-content-full bg-gray-lighter border-t">
			<div class="row">
				<div class="col-auto mr-auto">
				</div>
				<div class="col-auto">
					<input type="submit" name="save" value="Save & Add Next Exhibitor" class="btn btn-primary">
					<button type="submit" class="btn btn-primary">{{ __('general.save') }} & Edit</button>
				</div>
			</div>
		</div>
	</form>
</div>
@endsection

@push('script')
<script type="text/javascript" src="{{ asset('plugins/summernote/summernote-bs4.min.js') }}"></script>
<script type="text/javascript">
	$(function(){
		$('#field-about').summernote({
			height: 400,
			tooltip: false,
			toolbar: [
			    ['style', ['bold', 'italic', 'underline', 'clear']],
			    ['para', ['ul', 'ol']],
			    ['insert', ['picture', 'link', 'video', 'table', 'hr']]
		    ]
		});

	})
</script>
@endpush

@push('style')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/summernote/summernote-bs4.css') }}">
@endpush
