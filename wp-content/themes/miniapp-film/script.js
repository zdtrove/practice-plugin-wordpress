const __hiddenClass = 'd-none';
let ids2 = [];

function openEditModal(id) {
  const modalOverlay = document.getElementById('overlay');
  const modal = document.getElementById(`modal-edit-film-${id}`);
  const uploadImageBtn = modal.querySelector('.upload-poster-button');

  modalOverlay.classList.remove(__hiddenClass);
  modal.classList.remove(__hiddenClass);

  !ids2.includes(id) && uploadImageBtn && uploadImageBtn.addEventListener('click', function(e) {
    console.log('aaa');
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
    })
    .open();
  });

  ids2.push(id);
}

function hideModal(id) {
  const modalOverlay = document.getElementById('overlay');
  const modal = document.getElementById(id);
  modalOverlay.classList.add(__hiddenClass);
  modal.classList.add(__hiddenClass);
}

window.addEventListener('load', function() {
  const uploadImageBtn = document.querySelector('.upload-poster-button');
  uploadImageBtn && uploadImageBtn.addEventListener('click', function(e) {
    console.log('dlkfdlkfdlkf');
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
    })
    .open();
  });
});
