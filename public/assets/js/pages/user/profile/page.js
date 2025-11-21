document.addEventListener('DOMContentLoaded', function () {
    const avatarInput = document.getElementById('avatar-fileinput');
    const avatarPreview = document.getElementById('avatar-preview');
    const avatarPreviewContainer = document.getElementById('avatar-preview-container');
    const saveAvatarBtn = document.getElementById('btn-save');
    const removeBtn = document.getElementById('btn-remove');
    const dropzone = document.getElementById('dropzone');

    // Handle file selection
    avatarInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const file = this.files[0];

            // Validate file size (5MB max)
            if (file.size > 5 * 1024 * 1024) {
                $.alert({
                    title: 'Peringatan',
                    content: 'Ukuran file terlalu besar. Mohon pilih file dengan ukuran tidak lebih dari 5MB.',
                    type: 'red',
                    theme: 'material',
                    backgroundDismissAnimation: 'shake',
                    onOpenBefore: function () {
                        this.$title.css("color", "black");
                        this.$content.css("color", "black");
                    }
                });
                return;
            }

            const reader = new FileReader();
            reader.onload = function (e) {
                avatarPreview.src = e.target.result;
                avatarPreviewContainer.style.display = 'block';
                saveAvatarBtn.disabled = false;
                removeBtn.style.display = 'block';
                dropzone.style.display = 'none';
            }
            reader.readAsDataURL(file);
        }
    });

    // Handle remove button
    removeBtn.addEventListener('click', function () {
        resetPreview();
    });

    // Drag and drop functionality
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropzone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropzone.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        dropzone.style.borderColor = '#0d6efd';
        dropzone.style.backgroundColor = 'rgba(13, 110, 253, 0.05)';
    }

    function unhighlight() {
        dropzone.style.borderColor = '#dee2e6';
        dropzone.style.backgroundColor = 'rgba(108,117,125,0.1)';
    }

    dropzone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files.length) {
            avatarInput.files = files;
            const event = new Event('change');
            avatarInput.dispatchEvent(event);
        }
    }

    // Function to reset preview
    function resetPreview() {
        avatarInput.value = '';
        avatarPreviewContainer.style.display = 'none';
        saveAvatarBtn.disabled = true;
        removeBtn.style.display = 'none';
        dropzone.style.display = 'block';
        avatarPreview.src = '#';
    }
});


// Profile Edit/Save/Cancel functionality
const editBtn = document.getElementById('btn-edit');
const saveProfileBtn = document.getElementById('btn-save-profile');
const cancelBtn = document.getElementById('btn-cancel');
const profileForm = document.getElementById('profile-form');
const nameInput = profileForm.querySelector('input[name="name"]');
const userNameInput = profileForm.querySelector('input[name="username"]');
const phoneInput = profileForm.querySelector('input[name="phone"]');
const emailInput = profileForm.querySelector('input[name="email"]');

// Edit button click handler
editBtn.addEventListener('click', function () {
    // Enable inputs
    nameInput.disabled = false;
    userNameInput.disabled = false;
    phoneInput.disabled = false;
    emailInput.disabled = false;

    // Show save and cancel buttons
    saveProfileBtn.style.display = 'block';
    cancelBtn.style.display = 'block';

    // Hide edit button
    editBtn.style.display = 'none';
});

// Cancel button click handler
cancelBtn.addEventListener('click', function () {
    // Disable inputs
    nameInput.disabled = true;
    userNameInput.disabled = true;
    phoneInput.disabled = true;
    emailInput.disabled = true;

    // Reset form values
    profileForm.reset();

    // Show edit button
    editBtn.style.display = 'block';

    // Hide save and cancel buttons
    saveProfileBtn.style.display = 'none';
    cancelBtn.style.display = 'none';
});

// Save button click handler
saveProfileBtn.addEventListener('click', function () {
    // Submit the form
    profileForm.submit();
});

// Handle form submission
profileForm.addEventListener('submit', function (e) {
    e.preventDefault();

    // You can add validation here if needed

    // Show loading state
    saveProfileBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...';
    saveProfileBtn.disabled = true;

    // Submit form via AJAX
    fetch(profileForm.action, {
        method: 'POST',
        body: new FormData(profileForm),
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                $.alert({
                    title: 'Sukses',
                    content: data.message || 'Profil berhasil diperbarui!',
                    type: 'green',
                    theme: 'material',
                    onClose: function () {
                        // Reload page to reflect changes
                        window.location.reload();
                    }
                });
            } else {
                // Show error message
                $.alert({
                    title: 'Error',
                    content: data.message || 'Terjadi kesalahan saat memperbarui profil',
                    type: 'red',
                    theme: 'material'
                });
            }
        })
        .catch(error => {
            $.alert({
                title: 'Error',
                content: 'Terjadi kesalahan saat memperbarui profil',
                type: 'red',
                theme: 'material'
            });
        })
        .finally(() => {
            // Reset button state
            saveProfileBtn.innerHTML = 'Simpan';
            saveProfileBtn.disabled = false;
        });
});

// Password visibility toggle (tetap sama)
document.querySelectorAll('.toggle-password').forEach(function (element) {
    element.addEventListener('click', function () {
        const targetId = this.getAttribute('data-target');
        const passwordInput = document.getElementById(targetId);
        const icon = this.querySelector('i');

        // Toggle password field type
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);

        // Toggle eye icon
        icon.classList.toggle('ti-eye-off');
        icon.classList.toggle('ti-eye');
    });
});

// Handle form submission with AJAX
document.getElementById('passwordForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('#btn-save');
    const originalBtnText = submitBtn.innerHTML;

    // Show loading state
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
    submitBtn.disabled = true;

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.redirect) {
                $.alert({
                    title: 'Sukses',
                    content: data.message || 'Password berhasil diubah!',
                    type: 'green',
                    theme: 'material',
                    onClose: function () {
                        window.location.href = data.redirect;
                    }
                });
            } else {
                $.alert({
                    title: 'Sukses',
                    content: data.message || 'Password berhasil diubah!',
                    type: 'green',
                    theme: 'material',
                    onClose: function () {
                        form.reset();
                        bootstrap.Modal.getInstance(document.getElementById('modal-password')).hide();
                    }
                });
            }
        })
        .catch(error => {
            // Kembalikan teks tombol ke semula
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;

            // Tampilkan alert error
            let errorMessage = 'Terjadi kesalahan. Silakan coba lagi.';

            if (error.errors) {
                errorMessage = Object.values(error.errors).join('<br>');
            } else if (error.message) {
                errorMessage = error.message;
            }

            $.alert({
                title: 'Peringatan',
                content: errorMessage,
                type: 'red',
                theme: 'material',
                backgroundDismissAnimation: 'shake',
                onOpenBefore: function () {
                    this.$title.css("color", "black");
                    this.$content.css("color", "black");
                }
            });
        });
});
