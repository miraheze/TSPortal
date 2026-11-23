@props( [ 'value' ] )

@if ( $value === null )
	<span class="text-gray-600" title="{{ __( 'transparency-suppressed-title' ) }}">&lt;5</span>
@else
	{{ number_format( $value ) }}
@endif
