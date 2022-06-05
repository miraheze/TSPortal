@if ( $standing == 'Good' )
	<span class="badge bg-success">{{ __('standing-good') }}</span>
@elseif ( $standing == 'Sanctioned' )
	<span class="badge bg-warning">{{ __('standing-sanctioned') }}</span>
@elseif ( $standing == 'Banned' )
	<span class="badge bg-danger">{{ __('standing-banned') }}</span>
@endif
