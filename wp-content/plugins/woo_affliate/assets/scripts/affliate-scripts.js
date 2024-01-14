const _hiddenClass = 'd-none';
const _activeClass = 'active';

window.addEventListener('load', function() {
  /* Tabs */
  const _sPageURL = window.location.search.substring(1);
  const _params = _sPageURL.split('&');
  console.log(_params);
  
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
