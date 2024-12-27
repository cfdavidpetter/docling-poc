import './bootstrap';

document.getElementById('uploadForm')?.addEventListener('submit', function(e) {
  document.getElementById('loadingContainer').classList.remove('d-none');
  document.getElementById('uploadForm').classList.add('d-none');

  let timer = 0;
  let timerInterval = setInterval(function() {
    timer++;
    document.getElementById('timer').innerText = timer;
  }, 1000);
});
