const __hiddenClass = 'd-none';
const __activeClass = 'active';
let _ids = [];
let idsEpisode = [];

function changeUrl(tabId) {
  if (tabId == 1) {
    window.history.pushState('', '', '?page=danh-sach-phim&paged=1&tab=setting1');
  }
  if (tabId == 2) {
    window.history.pushState('', '', '?page=danh-sach-phim&paged=1&tab=setting2');
  }
}

function openEditModal(id) {
  const modalOverlay = document.getElementById('overlay');
  const modal = document.getElementById(`modal-edit-film-${id}`);
  const uploadImageBtn = modal.querySelector('.upload-poster-button');

  modalOverlay.classList.remove(__hiddenClass);
  modal.classList.remove(__hiddenClass);

  !_ids.includes(id) && uploadImageBtn && uploadImageBtn.addEventListener('click', function(e) {
    e.preventDefault();
    uploader = wp.media({
      title: 'Đổi poster',
      button: {
        text: 'Sử dụng ảnh này'
      },
      multiple: false
    }).on('select', function() {
      const attachment = uploader.state().get('selection').first().toJSON();
      const modal = document.getElementById(`modal-edit-film-${id}`);
      modal.querySelector('.poster-url').value = attachment.url;
      img = document.createElement('img');
      img.src = attachment.url;
      const wrapper = modal.querySelector('.poster-wrapper');
      wrapper.innerHTML = '';
      wrapper.appendChild(img);
    }).open();
  });

  _ids.push(id);
}

function openEpisodeModal(id) {
  const modalOverlay = document.getElementById('overlay');
  const modal = document.getElementById(`modal-list-episode-${id}`);
  const uploadImageBtn = modal.querySelectorAll('.upload-video-button');

  modalOverlay.classList.remove(__hiddenClass);
  modal.classList.remove(__hiddenClass);

  if (!idsEpisode.includes(id) && uploadImageBtn.length) {
    uploadImageBtn.forEach((item) => {
      item.addEventListener('click', function(e) {
        e.preventDefault();
        uploader = wp.media({
          title: 'Đổi video',
          button: {
            text: 'Sử dụng video này'
          },
          multiple: false
        }).on('select', function() {
          const attachment = uploader.state().get('selection').first().toJSON();
          item.nextElementSibling.innerHTML = attachment.url;
          item.parentElement.nextElementSibling.querySelector('.video-url').value = attachment.url;
        }).open();
      });
    })
  }
  idsEpisode.push(id);
}

function hideModal(id) {
  const modalOverlay = document.getElementById('overlay');
  const modal = document.getElementById(id);
  modalOverlay.classList.add(__hiddenClass);
  modal.classList.add(__hiddenClass);
}

function submitForm(formId) {
  const form = document.getElementById(formId);
  const requireInput = form.querySelectorAll('.require-field');

  requireInput.forEach((input) => {
    if (input.value === '') {
      input.nextElementSibling.classList.remove(__hiddenClass);
    } else {
      input.nextElementSibling.classList.add(__hiddenClass);
      form.submit();
    }
  });
}

window.addEventListener('load', function() {
  /* Common */
  const removeItem = document.querySelectorAll('.page-title-action');
  removeItem.forEach((item) => {
    if (item.innerHTML == 'Import' || item.innerHTML == 'Export') {
      item.classList.add(__hiddenClass);
    }
  });

  const _removeMessageBtn = document.getElementById('remove-message');
  _removeMessageBtn && _removeMessageBtn.addEventListener('click', function() {
    document.getElementById('message').classList.add(__hiddenClass);
  });

  /* Tabs */
  const __sPageURL = window.location.search.substring(1);
  const __params = __sPageURL.split('&');
  
  const _tabSetting1 = document.getElementById('tabSetting1');
  const _tabSetting2 = document.getElementById('tabSetting2');
  const _tabSetting1Content = document.getElementById('tab-setting-1-content');
  const _tabSetting2Content = document.getElementById('tab-setting-2-content');

  function clickChangeTab(tabIndex) {
    const listTab = [_tabSetting1, _tabSetting2];
    const listContentTab = [_tabSetting1Content, _tabSetting2Content];
    listTab.forEach((tab, index) => {
      Number(tabIndex) === Number(index + 1) ? tab.classList.add(__activeClass) : tab.classList.remove(__activeClass);
    });
    listContentTab.forEach((tabContent, index) => {
      Number(tabIndex) === Number(index + 1) ? tabContent.classList.add(__activeClass) : tabContent.classList.remove(__activeClass);
    });
  }

  if (_tabSetting1) {
    if (__params.length === 1 || __params[2].split('=')[1] === 'setting1') {
      clickChangeTab(1);
    } else if (__params[2].split('=')[1] === 'setting2') {
      clickChangeTab(2);
    }
  }
  
  const _tabs = document.querySelectorAll('ul.nav-tabs-film > li');
  for (i = 0; i < _tabs.length; i++) {
    _tabs[i].addEventListener('click', _switchTab);
  }

  function _switchTab(event) {
    event.preventDefault();
    document.querySelector('ul.nav-tabs-film li.active').classList.remove(__activeClass);
    document.querySelector('.tab-pane-film.active').classList.remove(__activeClass);
    const clickedTab = event.currentTarget;
    const anchor = event.target;
    const activePaneID = anchor.getAttribute('href');
    clickedTab.classList.add(__activeClass);
    document.querySelector(activePaneID).classList.add(__activeClass);
  }

  const buttonAddFilm = document.querySelector('.button-add-film');
  const modalAddFilm = document.querySelector('.modal-add-film');
  const modalOverlay = document.getElementById('overlay');
  const uploadImageBtn = document.querySelector('.upload-poster-button');

  buttonAddFilm && buttonAddFilm.addEventListener('click', function() {
    modalOverlay.classList.remove(__hiddenClass);
    modalAddFilm.classList.remove(__hiddenClass);
  });

  uploadImageBtn && uploadImageBtn.addEventListener('click', function(e) {
    e.preventDefault();
    uploader = wp.media({
      title: 'Thêm hình ảnh',
      button: {
        text: 'Sử dụng ảnh này'
      },
      multiple: false
    }).on('select', function() {
      const attachment = uploader.state().get('selection').first().toJSON();
      const tdRecord = document.getElementById('tr-add-film');
      tdRecord.querySelector('.poster-url').value = attachment.url;
      img = document.createElement('img');
      img.src = attachment.url;
      const wrapper = tdRecord.querySelector('.poster-wrapper');
      wrapper.innerHTML = '';
      wrapper.appendChild(img);
    }).open();
  });
});
