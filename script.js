document.getElementById('date_progress_shortcode_generator').addEventListener('submit', (event) => {
    event.preventDefault();
    let radio = document.querySelector('input[name="date_progress_radio"]:checked').value
    let attributes = [
        ['label', document.getElementById('date_progress_label').value],
        ['format', document.getElementById('date_progress_format').value],
        ['color', document.getElementById('date_progress_color').value],
        ['striped', document.getElementById('date_progress_striped').checked],
        ['animated', document.getElementById('date_progress_animated').checked],
        ['start', document.getElementById('date_progress_start').value],
        [radio, document.getElementById('date_progress_' + radio).value],
        ['repeating', document.getElementById('date_progress_repeating').checked && radio === 'duration']
    ]
    document.getElementById('date_progress_shortcode').value
        = `[date_progress ${attributes.filter(([_, v]) => v).map(([k, v]) => `${k}="${v}"`).join(' ')}]`
});