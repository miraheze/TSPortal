/**
 * To enable copy-to-clipboard:
 * - Add the "copyToClipboard" class to the trigger element
 * - Add data-copy="elementId" pointing to the element to copy from
 */
function copyToClipboard ( event ) {
	const target = event.currentTarget;
	const id = target.dataset.copy;

	if ( !id ) {
		console.warn(
			'Missing or empty data-copy attribute. Nothing copied.',
			target
		);
		return;
	}

	const copyElement = document.getElementById( id );
	if ( !copyElement ) {
		console.warn(
			`No element found with id "${id}". Nothing copied.`,
			target
		);
		return;
	}

	navigator.clipboard.writeText( copyElement.innerText ).catch( ( err ) => {
		console.warn( 'Clipboard write failed.', err );
	} );
}

document.querySelectorAll( '.copyToClipboard' ).forEach( ( el ) => {
	el.addEventListener( 'click', copyToClipboard );
} );
