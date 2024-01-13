const _hiddenClass = 'd-none';
const _activeClass = 'active';

window.addEventListener('load', function() {
  /* Tabs */
  sPageURL = window.location.search.substring(1);
  const params = sPageURL.split('&');
  
  const tabSetting1 = document.getElementById('tabSetting1');
  const tabSetting2 = document.getElementById('tabSetting2');
  const tabSetting1Content = document.getElementById('tab-setting-1-content');
  const tabSetting2Content = document.getElementById('tab-setting-1-content');

  if (tabSetting1) {
    if (params.length === 1 || params[2].split('=')[1] === 'setting1') {
      tabSetting1.classList.add(_activeClass);
      tabSetting2.classList.remove(_activeClass);
      tabSetting1Content.classList.add(_activeClass);
      tabSetting2Content.classList.remove(_activeClass);
    } else if (params[2].split('=')[1] === 'setting1') {
      tabSetting1.classList.remove(_activeClass);
      tabSetting2.classList.add(_activeClass);
      tabSetting1Content.classList.remove(_activeClass);
      tabSetting2Content.classList.add(_activeClass);
    }
  
    const tabs = document.querySelectorAll('ul.nav-tabs > li');
    for (i = 0; i < tabs.length; i++) {
      tabs[i].addEventListener('click', switchTab);
    }
  
    function switchTab(event) {
      event.preventDefault();
      document.querySelector('ul.nav-tabs li.active').classList.remove(_activeClass);
      document.querySelector('.tab-pane.active').classList.remove(_activeClass);
      const clickedTab = event.currentTarget;
      const anchor = event.target;
      const activePaneID = anchor.getAttribute('href');
      clickedTab.classList.add(_activeClass);
      document.querySelector(activePaneID).classList.add(_activeClass);
    }
  }
});
