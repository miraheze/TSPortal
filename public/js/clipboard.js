/*
 * To make this script copy elements to the clipboard, add the "copyToClipboard" button to a copy to clipboard element.
 * Then add a 'data-copy="idOfTheElementYouWantToCopy"' attribute to it.
*/
function copyToClipboard(event) {
	try {
		if (event.target.dataset.copy = "") {
			console.warning('Element has an empty data-copy attribute. Nothing was copied to the clipboard', event.target);
			return;
		}
	} catch (e) {
		console.warning('Error when accesing data-copy attribute. Nothing was copied to the clipboard.', event.target, e);
		return;
	}
	let copyElement = document.getElementById(event.target.dataset.copy);
	navigator.clipboard.writeText(copyElement.innerText);
}
let copyElements = document.getElementsByClassName("copyToClipboard");
for (let i = 0; i < copyElements.length; i++) {
	copyElements[i].addEventListener("click", copyToClipboard)
}
