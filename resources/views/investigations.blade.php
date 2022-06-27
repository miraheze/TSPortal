<x-layout>
	<x-slot name="pgname">
		{{ __('investigations') }}
	</x-slot>
	<x-slot name="content">
		<h3 class="text-dark mb-4">{{ __('open-investigations') }}</h3>
		<div class="card shadow mb-4">
			<div class="card-body text-center">
				<form>
					<div class="row container-fluid">
						<div class="col col-sm-4">
							<select class="form-select form-control" id="filter" onchange="updateFilter()">
								<option value="">----</option>
								<option value="type" {{ request()->input( 'type' ) ? 'selected' : '' }}>{{ __('topic') }}</option>
								<option value="recommendation" {{ request()->input( 'recommendation' ) ? 'selected' : '' }}>{{ __('recommendation') }}</option>
							</select>
						</div>
						<div class="col col-sm-1">
							<p>{{ __('is') }}</p>
						</div>
						<div class="col col-sm-5">
							<select class="form-select form-control toggle-hideall" name="type" id="filter-type" @disabled(!request()->input('type'))>
								<option value="">----</option>
								@foreach( config('app.investigationTopics') as $topic )
									<option value="{{ $topic }}" {{ ( request()->input( 'type' ) == $topic ) ? 'selected' : '' }}>... {{ __('investigation-topic-' . $topic) }}.</option>
								@endforeach
							</select>
							<select class="form-select form-control toggle-hideall" name="recommendation" id="filter-recommendation">
								@foreach ( config('app.recommendations') as $recommend )
									<option
										value="{{ $recommend }}" {{ ( request()->input( 'recommendation' ) == $recommend ) ? 'selected' : '' }}>
										... {{ __('recommendation-' . $recommend ) }}
									</option>
								@endforeach
							</select>
						</div>
						<div class="col col-sm-2">
							<div class="form-check">
								<input id="formCheck-1" class="form-check-input" name="closed" value="true" type="checkbox" {{ request()->input( 'closed' ) ? 'checked' : '' }}/>
								<label class="form-check-label" for="formCheck-1">{{ __('filter-closed') }}</label>
							</div>
						</div>
					</div>
					<div class="row container-fluid justify-content-center">
						<button class="btn btn-primary" type="submit" style="padding: 4px 8px; margin: 10px 0 0 25px; width: auto"><i
								class="fa-solid fa-filter fa-sm text-white-50"></i> {{ __('filter') }}
						</button>
					</div>
				</form>
			</div>
		</div>
		<div class="card shadow">
			<div class="card-body">
				<div class="table-responsive table mt-2">
					<table class="table my-0 text-center align-middle">
						<thead>
						<tr>
							<th style="width: 10%;">{{ __('id') }}</th>
							<th style="width: 20%;">{{ __('topic') }}</th>
							<th style="width: 40%;">{{ __('subject') }}</th>
							<th style="width: 15%;">{{ __('assigned') }}</th>
							<th style="width: 15%;">{{ __('status') }}</th>
						</tr>
						</thead>
						<tbody>
						@foreach ( $investigations as $investigation )
							<tr>
								<td><a class="nav-link"
								       href="/investigation/{{ $investigation->id }}">{{ $investigation->id }}</a></td>
								<td>{{ ucfirst(__('investigation-topic-' . $investigation->type)) }}</td>
								<td><a class="nav-link"
								       href="/user/{{ $investigation->subject->id }}">{{ $investigation->subject->username }}</a>
								</td>
								<td>{{ $investigation->assigned->username }}</td>
								@if ( $investigation->closed )
									<td><span class="badge bg-danger">{{ __('status-closed') }}</span></td>
								@else
									<td><span class="badge bg-success">{{ __('status-open') }}</span></td>
								@endif
							</tr>
						@endforeach
						</tbody>
						<tfoot>
						<tr>
							<th style="width: 10%;">{{ __('id') }}</th>
							<th style="width: 20%;">{{ __('topic') }}</th>
							<th style="width: 40%;">{{ __('subject') }}</th>
							<th style="width: 15%;">{{ __('assigned') }}</th>
							<th style="width: 15%;">{{ __('status') }}</th>
						</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	</x-slot>
	<x-slot name="scripts">
		<script src="/js/filter.js"></script>
	</x-slot>
</x-layout>
