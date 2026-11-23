@php
	$sub = __( 'transparency-period-' . ( $half ?: 'year' ) );
	$period = $half > 0 ? $sub . ' ' . $year : (string)$year;
	if ( $stats !== null ) {
		$reports = $stats['reports'];
		$investigations = $stats['investigations'];
		$appeals = $stats['appeals'];
		$dpa = $stats['dpa'];
		$visible = static fn ( string $column ): array => array_filter( array_column( $stats['monthly'], $column ), static fn ( ?int $value ): bool => $value !== null );
		$maxReports = max( 1, ...$visible( 'reports' ) ?: [ 1 ] );
		$maxInvestigations = max( 1, ...$visible( 'investigations' ) ?: [ 1 ] );
	}
@endphp

<x-layout>
	<x-slot name="pgname">
		{{ __( 'transparency' ) }}
	</x-slot>
	<x-slot name="content">
		<h3 class="text-dark mb-1">{{ __( 'transparency' ) }}@if ( $year > 0 ) {{ $year }}@endif</h3>
		@if ( $stats !== null )
			<h5 class="text-gray-600 mb-4">{{ $sub }}</h5>
		@endif
		<p>{{ __( 'transparency-intro' ) }}</p>
		@if ( $stats === null )
			<div class="alert alert-info">{{ __( 'transparency-none' ) }}</div>
		@else
		<div class="card shadow mb-4">
			<div class="card-body text-center">
				<form>
					<div class="row container-fluid justify-content-center align-items-center">
						<div class="col col-sm-2">
							<label class="form-label mb-0" for="year">{{ __( 'transparency-year' ) }}</label>
						</div>
						<div class="col col-sm-2">
							<select class="form-select form-control" name="year" id="year">
								@foreach ( array_keys( $available ) as $option )
									<option value="{{ $option }}" @selected( $option === $year )>{{ $option }}</option>
								@endforeach
							</select>
						</div>
						<div class="col col-sm-2">
							<label class="form-label mb-0" for="half">{{ __( 'transparency-period' ) }}</label>
						</div>
						<div class="col col-sm-3">
							<select class="form-select form-control" name="half" id="half">
								@foreach ( [ 0 => 'year', 1 => 1, 2 => 2 ] as $value => $key )
									<option value="{{ $value }}" @selected( $value === $half )>{{ __( 'transparency-period-' . $key ) }}</option>
								@endforeach
							</select>
						</div>
						<div class="col col-sm-2">
							<button class="btn btn-primary" type="submit" style="padding: 4px 8px; width: auto">
								<i class="fa-solid fa-filter fa-sm text-white-50"></i> {{ __( 'filter' ) }}
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6 col-xl-3 mb-4">
				<div class="card shadow border-start-success py-2">
					<div class="card-body">
						<div class="row align-items-center no-gutters">
							<div class="col me-2">
								<div class="text-uppercase text-success fw-bold text-xs mb-1"><span>{{ __( 'reports' ) }}</span></div>
								<div class="text-dark fw-bold h5 mb-0"><span><x-transparency.count :value="$reports['requested']"/></span></div>
								<div class="small text-gray-600">{{ __( 'transparency-completed' ) }}: <x-transparency.count :value="$reports['completed']"/></div>
							</div>
							<div class="col-auto"><i class="fa-solid fa-triangle-exclamation fa-2x text-gray-300"></i></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-xl-3 mb-4">
				<div class="card shadow border-start-primary py-2">
					<div class="card-body">
						<div class="row align-items-center no-gutters">
							<div class="col me-2">
								<div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>{{ __( 'investigations' ) }}</span></div>
								<div class="text-dark fw-bold h5 mb-0"><span><x-transparency.count :value="$investigations['requested']"/></span></div>
								<div class="small text-gray-600">{{ __( 'transparency-completed' ) }}: <x-transparency.count :value="$investigations['completed']"/></div>
							</div>
							<div class="col-auto"><i class="fa-solid fa-magnifying-glass fa-2x text-gray-300"></i></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-xl-3 mb-4">
				<div class="card shadow border-start-warning py-2">
					<div class="card-body">
						<div class="row align-items-center no-gutters">
							<div class="col me-2">
								<div class="text-uppercase text-warning fw-bold text-xs mb-1"><span>{{ __( 'appeals' ) }}</span></div>
								<div class="text-dark fw-bold h5 mb-0"><span><x-transparency.count :value="$appeals['requested']"/></span></div>
								<div class="small text-gray-600">{{ __( 'transparency-completed' ) }}: <x-transparency.count :value="$appeals['completed']"/></div>
							</div>
							<div class="col-auto"><i class="fa-solid fa-gavel fa-2x text-gray-300"></i></div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-xl-3 mb-4">
				<div class="card shadow border-start-info py-2">
					<div class="card-body">
						<div class="row align-items-center no-gutters">
							<div class="col me-2">
								<div class="text-uppercase text-info fw-bold text-xs mb-1"><span>{{ __( 'dpa' ) }}</span></div>
								<div class="text-dark fw-bold h5 mb-0"><span><x-transparency.count :value="$dpa['requested']"/></span></div>
								<div class="small text-gray-600">{{ __( 'transparency-completed' ) }}: <x-transparency.count :value="$dpa['completed']"/></div>
							</div>
							<div class="col-auto"><i class="fa-solid fa-user-slash fa-2x text-gray-300"></i></div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xl-6 mb-4">
				<div class="card shadow h-100">
					<div class="card-header py-3">
						<h6 class="text-primary fw-bold m-0">{{ __( 'reports' ) }}</h6>
					</div>
					<div class="card-body">
						<p class="small text-gray-600">{{ __( 'transparency-reports-desc', [ 'period' => $period ] ) }}</p>
						<x-transparency.summary :rows="[
							[ __( 'transparency-requested' ), $reports['requested'] ],
							[ __( 'transparency-completed' ), $reports['completed'] ],
							[ __( 'transparency-led-to-investigation' ), $reports['outcomes']['investigation'] ],
							[ __( 'transparency-led-to-dpa' ), $reports['outcomes']['dpa'] ],
							[ __( 'transparency-closed-no-action' ), $reports['outcomes']['closed'] ],
						]"/>
						<x-transparency.breakdown :rows="$reports['types']" prefix="report-topic-" :heading="__( 'topic' )" color="success"/>
					</div>
				</div>
			</div>
			<div class="col-xl-6 mb-4">
				<div class="card shadow h-100">
					<div class="card-header py-3">
						<h6 class="text-primary fw-bold m-0">{{ __( 'investigations' ) }}</h6>
					</div>
					<div class="card-body">
						<p class="small text-gray-600">{{ __( 'transparency-investigations-desc', [ 'period' => $period ] ) }}</p>
						<x-transparency.summary :rows="[
							[ __( 'transparency-requested' ), $investigations['requested'] ],
							[ __( 'transparency-completed' ), $investigations['completed'] ],
						]"/>
						<x-transparency.breakdown :rows="$investigations['topics']" prefix="investigation-topic-" :heading="__( 'topic' )"/>
						<x-transparency.breakdown :rows="$investigations['recommendations']" prefix="transparency-recommendation-" :heading="__( 'recommendation' )" color="info" :columns="[ 'requested' ]"/>
					</div>
				</div>
			</div>
		</div>

		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="text-primary fw-bold m-0">{{ __( 'transparency-actions' ) }}</h6>
			</div>
			<div class="card-body">
				<p class="small text-gray-600">{{ __( 'transparency-actions-desc', [ 'period' => $period ] ) }}</p>
				<div class="row">
					@foreach ( $stats['actions'] as $group => $rows )
						<div class="col-xl-4 mb-3">
							<x-transparency.breakdown :rows="$rows" prefix="events-" :heading="__( 'events-' . $group )" color="warning" :columns="[ 'completed' ]"/>
						</div>
					@endforeach
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xl-6 mb-4">
				<div class="card shadow h-100">
					<div class="card-header py-3">
						<h6 class="text-primary fw-bold m-0">{{ __( 'appeals' ) }}</h6>
					</div>
					<div class="card-body">
						<p class="small text-gray-600">{{ __( 'transparency-appeals-desc', [ 'period' => $period ] ) }}</p>
						<x-transparency.summary :rows="[
							[ __( 'transparency-requested' ), $appeals['requested'] ],
							[ __( 'transparency-completed' ), $appeals['completed'] ],
							[ ucfirst( __( 'appeal-outcome-upheld' ) ), $appeals['upheld'] ],
							[ ucfirst( __( 'appeal-outcome-not-upheld' ) ), $appeals['not-upheld'] ],
						]"/>
						<div class="table-responsive table mt-2">
							<table class="table my-0 align-middle">
								<thead>
								<tr>
									<th style="width: 46%;">{{ __( 'appeal-type' ) }}</th>
									<th class="text-end">{{ __( 'transparency-requested' ) }}</th>
									<th class="text-end">{{ ucfirst( __( 'appeal-outcome-upheld' ) ) }}</th>
									<th class="text-end">{{ ucfirst( __( 'appeal-outcome-not-upheld' ) ) }}</th>
								</tr>
								</thead>
								<tbody>
								@foreach ( $appeals['types'] as $type => $counts )
									<tr>
										<td>{{ ucfirst( __( 'appeal-type-' . $type ) ) }}</td>
										<td class="text-end"><x-transparency.count :value="$counts['requested']"/></td>
										<td class="text-end"><x-transparency.count :value="$counts['upheld']"/></td>
										<td class="text-end"><x-transparency.count :value="$counts['not-upheld']"/></td>
									</tr>
								@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xl-6 mb-4">
				<div class="card shadow h-100">
					<div class="card-header py-3">
						<h6 class="text-primary fw-bold m-0">{{ __( 'dpa' ) }}</h6>
					</div>
					<div class="card-body">
						<p class="small text-gray-600">{{ __( 'transparency-dpa-desc', [ 'period' => $period ] ) }}</p>
						<x-transparency.summary :rows="[
							[ __( 'transparency-requested' ), $dpa['requested'] ],
							[ __( 'transparency-completed' ), $dpa['completed'] ],
							[ __( 'transparency-dpa-statutory' ), $dpa['statutory'] ],
							[ __( 'transparency-dpa-accepted' ), $dpa['accepted'] ],
							[ __( 'transparency-dpa-rejected' ), $dpa['rejected'] ],
						]"/>
					</div>
				</div>
			</div>
		</div>

		<div class="card shadow mb-4">
			<div class="card-header py-3">
				<h6 class="text-primary fw-bold m-0">{{ __( 'transparency-by-month' ) }}</h6>
			</div>
			<div class="card-body">
				<div class="table-responsive table mt-2">
					<table class="table my-0 align-middle">
						<thead>
						<tr>
							<th style="width: 10%;">{{ __( 'transparency-month' ) }}</th>
							<th style="width: 30%;">{{ __( 'reports' ) }}</th>
							<th style="width: 30%;">{{ __( 'investigations' ) }}</th>
							<th class="text-end" style="width: 15%;">{{ __( 'appeals' ) }}</th>
							<th class="text-end" style="width: 15%;">{{ __( 'dpa' ) }}</th>
						</tr>
						</thead>
						<tbody>
						@foreach ( $stats['monthly'] as $month => $counts )
							<tr>
								<td>{{ \Carbon\CarbonImmutable::createFromFormat( '!Y-m', $month )->format( 'M' ) }}</td>
								<td>
									<div class="d-flex align-items-center">
										<div class="progress flex-grow-1" style="height: 0.6rem;">
											<div class="progress-bar bg-success" role="progressbar" style="width: {{ round( ( $counts['reports'] ?? 0 ) / $maxReports * 100 ) }}%;"></div>
										</div>
										<span class="small ms-2 text-end" style="width: 2rem;"><x-transparency.count :value="$counts['reports']"/></span>
									</div>
								</td>
								<td>
									<div class="d-flex align-items-center">
										<div class="progress flex-grow-1" style="height: 0.6rem;">
											<div class="progress-bar bg-primary" role="progressbar" style="width: {{ round( ( $counts['investigations'] ?? 0 ) / $maxInvestigations * 100 ) }}%;"></div>
										</div>
										<span class="small ms-2 text-end" style="width: 2rem;"><x-transparency.count :value="$counts['investigations']"/></span>
									</div>
								</td>
								<td class="text-end"><x-transparency.count :value="$counts['appeals']"/></td>
								<td class="text-end"><x-transparency.count :value="$counts['dpa']"/></td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
		@endif
	</x-slot>
	<x-slot name="scripts">
		@if ( $stats !== null )
			<script>
				const available = @json( $available );
				const year = document.getElementById( 'year' );
				const half = document.getElementById( 'half' );
				const sync = () => {
					const open = available[year.value] || [];
					for ( const option of half.options ) {
						option.disabled = !open.includes( Number( option.value ) );
					}
					if ( half.selectedOptions[0].disabled ) {
						half.value = [ ...half.options ].find( ( option ) => !option.disabled && option.value !== '0' )?.value ?? '0';
					}
				};
				year.addEventListener( 'change', sync );
				sync();
			</script>
		@endif
	</x-slot>
</x-layout>
