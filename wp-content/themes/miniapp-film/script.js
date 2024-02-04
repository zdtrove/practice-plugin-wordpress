const __hiddenClass = 'd-none';
let _ids = [];
let idsEpisode = [];

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

window.addEventListener('load', function() {
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
