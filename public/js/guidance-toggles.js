function reportGuidance() {
	const current = document.getElementById('report').value;
	hideAll('toggle-hideall');
	document.getElementById('guidance-' + current.split('-').slice(1).join('-')).classList.remove('d-none');
}

function dpaEvidence() {
	const current = document.getElementById('username-type').value;
	hideAll('toggle-hideall');
	document.getElementById('evidence-' + current).classList.remove('d-none')
}

function hideAll(hideClass) {
	const elements = document.getElementsByClassName(hideClass);
	for (const id of elements) {
		id.classList.add('d-none');
	}
}
