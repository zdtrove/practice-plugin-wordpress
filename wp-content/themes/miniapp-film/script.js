const hiddenClass = 'd-none';

function openEditModal(id) {
  const modalOverlay = document.getElementById('overlay');
  const modal = document.getElementById(`modal-edit-film-${id}`);
  modalOverlay.classList.remove(hiddenClass);
  modal.classList.remove(hiddenClass);
}

function hideModal(id) {
  const modalOverlay = document.getElementById('overlay');
  const modal = document.getElementById(id);
  modalOverlay.classList.add(hiddenClass);
  modal.classList.add(hiddenClass);
}

window.addEventListener('load', function() {
  const uploadImageBtn = document.querySelector('.upload-poster-button');
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
    })
    .open();
  });
});