<x-layout>
	<x-slot name="pgname">
		{{ __('reports') }}
	</x-slot>
	<x-slot name="content">
		<h3 class="text-dark mb-4">{{ __('open-reports') }}</h3>
		<div class="card shadow mb-4">
			<div class="card-body text-center">
				<form>
					<div class="row container-fluid">
						<div class="col col-sm-4">
							<select class="form-select form-control" id="filter" onchange="updateFilter()">
								<option value="">----</option>
								<option value="type" {{ request()->input( 'type' ) ? 'selected' : '' }}>{{ __('topic') }}</option>
								<option value="investigation" {{ request()->input( 'investigation' ) ? 'selected' : '' }}>{{ __('investigation') }}</option>
							</select>
						</div>
						<div class="col col-sm-1">
							<p>{{ __('is') }}</p>
						</div>
						<div class="col col-sm-5">
							<select class="form-select form-control toggle-hideall" name="type" id="filter-type" @disabled(!request()->input('type'))>
								@foreach( config('app.reportTopics') as $type => $topics )
									<option value="">----</option>
									<optgroup label="{{ $type }}">
										@foreach( $topics as $topic )
											{{ $allTopics[] = $topic }}
											<option value="{{ $type . '-' . $topic }}" {{ ( request()->input( 'type' ) == $type . '-' . $topic ) ? 'selected' : '' }}>... {{ __('report-topic-' . $type . '-' . $topic) }}.</option>
										@endforeach
									</optgroup>
								@endforeach
							</select>
							<input class="form-control form-floating toggle-hideall" type="number" id="filter-investigation" name="investigation" value="{{ request()->input('investigation') }}">
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
				<div class="table-responsive table mt-2" id="dataTable" role="grid" aria-describedby="dataTable_info">
					<table class="table table-striped my-0 text-center align-middle" id="dataTable">
						<thead>
						<tr>
							<th style="width: 33%;">{{ __('id') }}</th>
							<th style="width: 33%;">{{ __('topic') }}</th>
							<th style="width: 33%;">{{ __('report-age') }}</th>
						</tr>
						</thead>
						<tbody>
						@foreach ( $reports as $report )
							<tr>
								<td><a class="nav-link" href="/report/{{ $report->id }}">{{ $report->id }}</a></td>
								<td>{{ ucfirst(__('report-topic-' . $report->type)) }}</td>
								<td>{{ $report->created->diffForHumans() }}</td>
							</tr>
						@endforeach
						</tbody>
						<tfoot>
						<tr>
							<th style="width: 33%;">{{ __('id') }}</th>
							<th style="width: 33%;">{{ __('topic') }}</th>
							<th style="width: 33%;">{{ __('report-age') }}</th>
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
