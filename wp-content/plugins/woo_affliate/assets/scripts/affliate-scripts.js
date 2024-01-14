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

function openIncomeModal(id) {
  const modalOverlay = document.querySelector('.overlay');
  const modalLowerLevel = document.querySelector(`.modal-income-${id}`);

  modalOverlay.classList.remove(_hiddenClass);
  modalLowerLevel.classList.remove(_hiddenClass);
}

function closeIncomeModal(id) {
  const modalOverlay = document.querySelector('.overlay');
  const modalLowerLevel = document.querySelector(`.modal-income-${id}`);

  modalOverlay.classList.add(_hiddenClass);
  modalLowerLevel.classList.add(_hiddenClass);
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
  const tabSetting1Content = document.getElementById('tab-setting-1-content');
  const tabSetting2Content = document.getElementById('tab-setting-2-content');

  if (tabSetting1) {
    if (_params.length === 1 || _params[1].split('=')[1] === 'setting1') {
      tabSetting1.classList.add(_activeClass);
      tabSetting2.classList.remove(_activeClass);
      tabSetting1Content.classList.add(_activeClass);
      tabSetting2Content.classList.remove(_activeClass);
    } else if (_params[1].split('=')[1] === 'setting2') {
      tabSetting1.classList.remove(_activeClass);
      tabSetting2.classList.add(_activeClass);
      tabSetting1Content.classList.remove(_activeClass);
      tabSetting2Content.classList.add(_activeClass);
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
