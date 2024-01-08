window.addEventListener('load', function() {
  /* Common */
  const removeMessageBtn = document.getElementById('remove-message');
  removeMessageBtn && removeMessageBtn.addEventListener('click', function() {
    document.getElementById('message').classList.add('d-none');
  });

  /* Tabs */
  const tabs = document.querySelectorAll('ul.nav-tabs > li');
  for (i = 0; i < tabs.length; i++) {
    tabs[i].addEventListener('click', switchTab);
  }

  function switchTab(event) {
    event.preventDefault();
    document.querySelector('ul.nav-tabs li.active').classList.remove('active');
    document.querySelector('.tab-pane.active').classList.remove('active');
    const clickedTab = event.currentTarget;
    const anchor = event.target;
    const activePaneID = anchor.getAttribute('href');
    clickedTab.classList.add('active');
    document.querySelector(activePaneID).classList.add('active');
  }

  /* Modal */
  let step = 1;
  let isEditAll = false;
  const modalOverlay = document.getElementById('overlay');
  const modalAddBtn = document.getElementById('modal-add-btn');
  const modalEditBtn = document.getElementById('modal-edit-btn');
  const modalAdd = document.getElementById('modal-add');
  const modalClose = document.getElementById('modal-close');
  const modalNext = document.getElementById('modal-next');
  const modalPrev = document.getElementById('modal-prev');
  const modalUpdate = document.getElementById('modal-update');

  modalAddBtn.addEventListener('click', function() {
    openModal();
  });

  modalEditBtn.addEventListener('click', function() {
    openModal();
  });

  modalClose.addEventListener('click', function() {
    modalOverlay.classList.add('d-none');
    modalAdd.classList.add('d-none');
  });

  modalNext.addEventListener('click', function() {
    step++;
    changeModalContent();
    modalPrev.classList.remove('d-none');
    document.getElementById(`step-${step}`).classList.add('current');
    for (let i = step - 1; i > 0; i--) {
      document.getElementById(`step-${i}`).classList.add('active');
    }
    if (step === 4) {
      modalNext.classList.add('d-none');
      modalUpdate.classList.remove('d-none');
    }
  });

  modalPrev.addEventListener('click', function() {
    document.getElementById(`step-${step}`).classList.remove('current');
    step--;
    document.getElementById(`step-${step}`).classList.remove('active');
    if (step === 1 || (step === 2 && isEditAll === false)) {
      modalPrev.classList.add('d-none');
    }
    if (step === 3) {
      modalNext.classList.remove('d-none');
      modalUpdate.classList.add('d-none');
    }
    changeModalContent();
  });

  function openModal() {
    step = isEditAll ? 1 : 2;
    modalPrev.classList.add('d-none');
    modalUpdate.classList.add('d-none');
    modalOverlay.classList.remove('d-none');
    modalAdd.classList.remove('d-none');
    modalNext.classList.remove('d-none');
    for (let i = 1; i <= 4; i++) {
      document.getElementById(`step-${i}`).classList.remove('current');
      document.getElementById(`step-${i}`).classList.remove('active');
    }
    document.getElementById(`step-${step}`).classList.add('current');
    for (let i = step - 1; i > 0; i--) {
      document.getElementById(`step-${i}`).classList.add('active');
    }
    changeModalContent();
  }

  function changeModalContent() {
    for (let i = 1; i <= 4; i++) {
      if (i === step) {
        document.getElementById(`content-step-${i}`).classList.remove('d-none');
      } else {
        document.getElementById(`content-step-${i}`).classList.add('d-none');
      }
    }
  }

  /* Image Upload */
  jQuery(function($){
    $('body').on('click', '.aw_upload_image_button', function(e){
      e.preventDefault();
      aw_uploader = wp.media({
        title: 'Thêm hình ảnh',
        button: {
          text: 'Sử dụng ảnh này'
        },
        multiple: false
      }).on('select', function() {
        var attachment = aw_uploader.state().get('selection').first().toJSON();
        $('#aw_custom_image').val(attachment.url);
        img = document.createElement('img');
        img.src = attachment.url;
        document.getElementById('image-wrapper').appendChild(img);
      })
      .open();
    });
  });

  /* Handle rank name */
  const rankNames = document.getElementsByName('name');
  const showRanks = document.getElementsByClassName('show-rank');
  rankNames.forEach((rankName, index) => {
    rankName.addEventListener('change', function(e) {
      showRanks[index].innerHTML = e.target.value;
    });
  });

  /* Handle limit input */
  const inputs = document.getElementsByName('is_limit');
  const div = document.getElementsByClassName('is-limit-content');
  inputs.forEach((input, index) => {
    input.addEventListener('click', function() {
      if (input.checked) {
        div[index].classList.remove('d-none');
        div[index].previousElementSibling.classList.add('d-none');
      } else {
        div[index].classList.add('d-none');
        div[index].previousElementSibling.classList.remove('d-none');
      }
    });
  });
});
