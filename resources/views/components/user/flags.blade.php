@if ( $flags )
	@foreach ( $flags as $flag )
		<span class="badge bg-secondary">{{ __('user-flags-' . $flag ) }}</span>
	@endforeach
@else
	<span class="badge bg-secondary">{{ __('user-flags-none') }}</span>
@endif
