@props( [ 'rows', 'prefix', 'heading', 'color' => 'primary', 'columns' => [ 'requested', 'completed' ] ] )

<div class="table-responsive table mt-2">
	<table class="table my-0 align-middle">
		<thead>
		<tr>
			<th>{{ $heading }}</th>
			@foreach ( $columns as $column )
				<th class="text-end" style="width: 15%;">{{ __( 'transparency-' . $column ) }}</th>
			@endforeach
			<th style="width: 30%;">{{ __( 'transparency-share' ) }}</th>
		</tr>
		</thead>
		<tbody>
		@foreach ( $rows as $row )
			<tr>
				<td>{{ ucfirst( __( $prefix . $row['key'] ) ) }}</td>
				@foreach ( $columns as $column )
					<td class="text-end"><x-transparency.count :value="$row[$column]"/></td>
				@endforeach
				<td>
					@if ( $row['percent'] !== null )
						<div class="d-flex align-items-center">
							<div class="progress flex-grow-1" style="height: 0.6rem;">
								<div class="progress-bar bg-{{ $color }}" role="progressbar" style="width: {{ $row['percent'] }}%;" aria-valuenow="{{ $row['percent'] }}" aria-valuemin="0" aria-valuemax="100"></div>
							</div>
							<span class="small text-gray-600 ms-2 text-end" style="width: 3rem;">{{ $row['percent'] }}%</span>
						</div>
					@endif
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
</div>
