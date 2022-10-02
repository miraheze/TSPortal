<x-layout>
	<x-slot name="pgname">
		{{ __('appeals') }}
	</x-slot>
	<x-slot name="content">
		<h3 class="text-dark mb-4">{{ __('open-appeals') }}</h3>
		<div class="card shadow mb-4">
			<div class="card-body text-center">
				<form>
					<div class="row container-fluid">
						<div class="col col-sm-4">
							<select class="form-select form-control" id="filter" onchange="updateFilter()">
								<option value="">----</option>
								<option value="type" {{ request()->input( 'type' ) ? 'selected' : '' }}>{{ __('appeal-type') }}</option>
								<option value="outcome" {{ request()->input( 'outcome' ) ? 'selected' : '' }}>{{ __('appeal-outcome') }}</option>
							</select>
						</div>
						<div class="col col-sm-1">
							<p>{{ __('is') }}</p>
						</div>
						<div class="col col-sm-5">
							<select class="form-select form-control toggle-hideall" name="type" id="filter-type" @disabled(!request()->input('type'))>
								<option value="">----</option>
								@foreach( config('app.appeals') as $type => $data )
									<option value="{{ $type }}" {{ ( request()->input( 'type' ) == $type ) ? 'selected' : '' }}>... {{ __('appeal-type-' . $type) }}.</option>
								@endforeach
							</select>
							<select class="form-select form-control toggle-hideall" name="outcome" id="filter-outcome">
								@foreach ( [ 'upheld', 'not-upheld' ] as $outcome )
									<option
										value="{{ $outcome }}" {{ ( request()->input( 'outcome' ) == $outcome ) ? 'selected' : '' }}>
										... {{ __('appeal-outcome-' . $outcome ) }}
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
							<th style="width: 20%;">{{ __('id') }}</th>
							<th style="width: 20%;">{{ __('appeal-type') }}</th>
							<th style="width: 20%;">{{ __('investigation') }}</th>
							<th style="width: 20%;">{{ __('assigned') }}</th>
							<th style="width: 20%;">{{ __('status') }}</th>
						</tr>
						</thead>
						<tbody>
						@foreach ( $appeals as $appeal )
							<tr>
								<td><a class="nav-link"
								       href="/appeal/{{ $appeal->id }}">{{ $appeal->id }}</a></td>
								<td>{{ ucfirst(__('appeal-type-' . $appeal->type)) }}</td>
								<td><a class="nav-link"
								       href="/investigation/{{ $appeal->investigation->id }}">{{ '#' . $appeal->investigation->id }}</a>
								</td>
								<td>{{ $appeal->assigned->username }}</td>
								@if ( $appeal->outcome == 'upheld' )
									<td><span class="badge bg-warning">{{ __('appeal-outcome-upheld') }}</span></td>
								@elseif ( $appeal->outcome == 'not-upheld' )
									<td><span class="badge bg-success">{{ __('appeal-outcome-not-upheld') }}</span></td>
								@else
									<td><span class="badge bg-danger">{{ __('status-open') }}</span></td>
								@endif
							</tr>
						@endforeach
						</tbody>
						<tfoot>
						<tr>
							<th style="width: 20%;">{{ __('id') }}</th>
							<th style="width: 20%;">{{ __('appeal-type') }}</th>
							<th style="width: 20%;">{{ __('investigation') }}</th>
							<th style="width: 20%;">{{ __('assigned') }}</th>
							<th style="width: 20%;">{{ __('status') }}</th>
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
