const _hiddenClass = 'd-none';
const _activeClass = 'active';

function openLowerModal(id) {
  const modalOverlay = document.querySelector('.overlay');
  const modalLowerLevel = document.querySelector(`.modal-lower-level-${id}`);

  modalOverlay.classList.remove(_hiddenClass);
  modalLowerLevel.classList.remove(_hiddenClass);
}

function closeLowerModal(id) {
  const modalOverlay = document.querySelector('.overlay');
  const modalLowerLevel = document.querySelector(`.modal-lower-level-${id}`);

  modalOverlay.classList.add(_hiddenClass);
  modalLowerLevel.classList.add(_hiddenClass);
}

function changeUrl(tabId) {
  if (tabId == 1) {
    window.history.pushState('', '', '?page=hoa-hong&paged=1&tab=setting1');
  }
  if (tabId == 2) {
    window.history.pushState('', '', '?page=hoa-hong&paged=1&tab=setting2');
  }
  if (tabId == 3) {
    window.history.pushState('', '', '?page=hoa-hong&paged=1&tab=setting3');
  }
  if (tabId == 4) {
    window.history.pushState('', '', '?page=hoa-hong&paged=1&tab=setting4');
  }
}

let checkSelect = 0;

function handleSubmit() {
  const arraySelect = ['dateFrom', 'monthFrom', 'yearFrom', 'dateTo', 'monthTo', 'yearTo'];
  const arrayTest = [];
  arraySelect.forEach((item) => {
    arrayTest.push(document.querySelector(`select[name="${item}"]`).value);
  });
  const check = arrayTest.every((item) => item !== '');

  if (check) {
    document.querySelector('.submitFilter').removeAttribute('disabled');
  } else {
    document.querySelector('.submitFilter').setAttribute('disabled', '');
  }
}

window.addEventListener('load', function() {
  /* Common */
  const removeMessageBtn = document.getElementById('remove-message');
  removeMessageBtn && removeMessageBtn.addEventListener('click', function() {
    document.getElementById('message').classList.add(hiddenClass);
  });
  /* Tabs */
  const _sPageURL = window.location.search.substring(1);
  const _params = _sPageURL.split('&');
  
  const tabSetting1 = document.getElementById('tabSetting1');
  const tabSetting2 = document.getElementById('tabSetting2');
  const tabSetting3 = document.getElementById('tabSetting3');
  const tabSetting4 = document.getElementById('tabSetting4');
  const tabSetting1Content = document.getElementById('tab-setting-1-content');
  const tabSetting2Content = document.getElementById('tab-setting-2-content');
  const tabSetting3Content = document.getElementById('tab-setting-3-content');
  const tabSetting4Content = document.getElementById('tab-setting-4-content');

  if (tabSetting1) {
    if (_params.length === 1 || _params[2].split('=')[1] === 'setting1') {
      tabSetting1.classList.add(_activeClass);
      tabSetting2.classList.remove(_activeClass);
      tabSetting3.classList.remove(_activeClass);
      tabSetting4.classList.remove(_activeClass);
      tabSetting1Content.classList.add(_activeClass);
      tabSetting2Content.classList.remove(_activeClass);
      tabSetting3Content.classList.remove(_activeClass);
      tabSetting4Content.classList.remove(_activeClass);
    } else if (_params[2].split('=')[1] === 'setting2') {
      tabSetting1.classList.remove(_activeClass);
      tabSetting2.classList.add(_activeClass);
      tabSetting3.classList.remove(_activeClass);
      tabSetting4.classList.remove(_activeClass);
      tabSetting1Content.classList.remove(_activeClass);
      tabSetting2Content.classList.add(_activeClass);
      tabSetting3Content.classList.remove(_activeClass);
      tabSetting4Content.classList.remove(_activeClass);
    } else if (_params[2].split('=')[1] === 'setting3') {
      tabSetting1.classList.remove(_activeClass);
      tabSetting2.classList.remove(_activeClass);
      tabSetting3.classList.add(_activeClass);
      tabSetting4.classList.remove(_activeClass);
      tabSetting1Content.classList.remove(_activeClass);
      tabSetting2Content.classList.remove(_activeClass);
      tabSetting3Content.classList.add(_activeClass);
      tabSetting4Content.classList.remove(_activeClass);
    } else if (_params[2].split('=')[1] === 'setting4') {
      tabSetting1.classList.remove(_activeClass);
      tabSetting2.classList.remove(_activeClass);
      tabSetting3.classList.remove(_activeClass);
      tabSetting4.classList.add(_activeClass);
      tabSetting1Content.classList.remove(_activeClass);
      tabSetting2Content.classList.remove(_activeClass);
      tabSetting3Content.classList.remove(_activeClass);
      tabSetting4Content.classList.add(_activeClass);
    }
  }
  
  const _tabs = document.querySelectorAll('ul.nav-tabs-affliate > li');
  for (i = 0; i < _tabs.length; i++) {
    _tabs[i].addEventListener('click', _switchTab);
  }

  function _switchTab(event) {
    event.preventDefault();
    document.querySelector('ul.nav-tabs-affliate li.active').classList.remove(_activeClass);
    document.querySelector('.tab-pane-affliate.active').classList.remove(_activeClass);
    const clickedTab = event.currentTarget;
    const anchor = event.target;
    const activePaneID = anchor.getAttribute('href');
    clickedTab.classList.add(_activeClass);
    document.querySelector(activePaneID).classList.add(_activeClass);
  }
});
