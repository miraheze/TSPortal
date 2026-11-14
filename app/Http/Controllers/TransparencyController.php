<?php

declare( strict_types = 1 );

namespace App\Http\Controllers;

use App\Support\TransparencyStats;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use function array_find_key;
use function view;

/**
 * Controller class for the transparency report.
 */
class TransparencyController
{
	/**
	 * Shows the aggregate figures for the selected period.
	 */
	public function index( Request $request ): View
	{
		$choices = TransparencyStats::choices();
		$default = array_find_key( $choices, static fn ( array $choice ): bool => $choice['half'] > 0 );
		$year = $request->integer( 'year', $default === null ? 0 : $choices[$default]['year'] );
		$half = $request->integer( 'half', $default === null ? 0 : $choices[$default]['half'] );

		$available = [];
		foreach ( $choices as $choice ) {
			$available[$choice['year']][] = $choice['half'];
		}

		$selected = $half === 0 ? (string)$year : $year . '-' . $half;
		if ( !isset( $choices[$selected] ) ) {
			$selected = array_find_key( $choices, static fn ( array $choice ): bool => $choice['year'] === $year && $choice['half'] > 0 ) ?? $default;
		}

		$current = $selected === null ? null : $choices[$selected];

		return view( 'transparency' )->with( [
			'available' => $available,
			'year' => $current['year'] ?? 0,
			'half' => $current['half'] ?? 0,
			'stats' => $current === null ? null : new TransparencyStats( $current['months'] )->all(),
		] );
	}
}
