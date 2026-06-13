document.addEventListener("DOMContentLoaded", function() {
    // page load na ho is lia 
    function loadPageContent(url, pushToHistory = true) {
        const contentArea = document.getElementById('main-app-content');
        contentArea.innerHTML = '<div class="text-center my-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Loading content...</p></div>';

        fetch(url)
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.getElementById('main-app-content');

                if (newContent) {
                    contentArea.innerHTML = newContent.innerHTML;
                    if (pushToHistory) {
                        history.pushState({ url: url }, '', url);
                    }
                    reinitializePageScripts(contentArea);
                } else {
                    contentArea.innerHTML = '<div class="alert alert-danger">Error: App content wrapper missing.</div>';
                }
            })
            .catch(err => {
                contentArea.innerHTML = '<div class="alert alert-danger">Failed to load section.</div>';
            });
    }

    document.addEventListener('click', function(e) {
        const link = e.target.closest('.spa-link');
        if (link) {
            e.preventDefault();
            const url = link.getAttribute('href');
            loadPageContent(url);
            document.querySelectorAll('.spa-link').forEach(el => el.classList.remove('active'));
            link.classList.add('active');
        }
    });

    window.addEventListener('popstate', function(e) {
        if (e.state && e.state.url) {
            loadPageContent(e.state.url, false);
        } else {
            loadPageContent(window.location.pathname, false);
        }
    });

    // Form Handle (ADD MEMBER ONLY)
    document.addEventListener('submit', function(e) {
        if (e.target && e.target.id === 'addMemberForm') {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            const alertBox = document.getElementById('memberAlert');
            const saveBtn = document.getElementById('saveBtn');

            if(saveBtn) saveBtn.disabled = true;

            fetch('../../api/add_member_api.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if(saveBtn) saveBtn.disabled = false;
                if(data.status === 'success') {
                    alertBox.className = "alert alert-success d-block fw-bold shadow-sm";
                    alertBox.innerHTML = '<i class="fa-solid fa-circle-check me-2"></i>' + data.message;
                    form.reset();
                    setTimeout(() => { alertBox.className = "alert d-none"; }, 4000);
                } else {
                    alertBox.className = "alert alert-danger d-block fw-bold shadow-sm";
                    alertBox.innerHTML = '<i class="fa-solid fa-circle-exclamation me-2"></i>' + data.message;
                }
            });
        }
    });

    // Edit Member Details
    document.addEventListener('click', function(e) {
        
        // --- MANUAL SAVE CHANGES BUTTON (EDIT FORM OVERRIDE) ---
        if (e.target && e.target.id === 'updateBtn') {
            e.preventDefault();
            const formEl = document.getElementById('editMemberForm');
            if(!formEl) return;

            const formData = new FormData(formEl);
            const alertBox = document.getElementById('modalAlert');
            const globalAlert = document.getElementById('listAlert');
            const updateBtn = e.target;

            updateBtn.disabled = true;

            fetch('../../api/update_member_api.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                updateBtn.disabled = false;
                if (data.status === 'success') {
                    globalAlert.className = "alert alert-success d-block shadow-sm fw-bold";
                    globalAlert.innerHTML = '<i class="fa-solid fa-circle-check me-2"></i>' + data.message;
                    
                    // Close Bootstrap Modal cleanly
                    const modalEl = document.getElementById('editModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalEl) || new bootstrap.Modal(modalEl);
                    modalInstance.hide();
                    
                    // Force backdrop removal if stuck
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                    document.body.style.overflow = 'auto';

                    // Update UI Row fields live
                    const id = document.getElementById('edit_id').value;
                    const row = document.getElementById('row-' + id);
                    const fname = document.getElementById('edit_fname').value;
                    const lname = document.getElementById('edit_lname').value;
                    
                    row.querySelector('.m-name').innerText = fname + ' ' + lname;
                    row.querySelector('.m-name').setAttribute('data-fname', fname);
                    row.querySelector('.m-name').setAttribute('data-lname', lname);
                    row.querySelector('.m-dob').innerText = document.getElementById('edit_dob').value;
                    row.querySelector('.m-gender').innerText = document.getElementById('edit_gender').value;
                    
                    const newStatus = document.getElementById('edit_status').value;
                    row.querySelector('.m-status-text').innerText = newStatus;
                    const badge = row.querySelector('.m-status-badge');
                    badge.innerText = newStatus;
                    badge.className = "badge m-status-badge " + (newStatus === 'Active' ? 'bg-success' : 'bg-danger');

                    setTimeout(() => { globalAlert.className = "alert d-none"; }, 3000);
                } else {
                    alertBox.className = "alert alert-danger d-block";
                    alertBox.innerText = data.message;
                }
            });
        }

        // --- DELETE ACTION TRIGGER ---
        const deleteBtn = e.target.closest('.delete-btn-spa');
        if (deleteBtn) {
            const id = deleteBtn.getAttribute('data-id');
            if (confirm("Are you sure you want to delete this member?")) {
                const alertBox = document.getElementById('listAlert');
                const formData = new FormData();
                formData.append('id', id);

                fetch('../../api/delete_member_api.php', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        alertBox.className = "alert alert-success d-block fw-bold shadow-sm";
                        alertBox.innerHTML = '<i class="fa-solid fa-circle-check me-2"></i>' + data.message;
                        document.getElementById('row-' + id).remove();
                        setTimeout(() => { alertBox.className = "alert d-none"; }, 3000);
                    }
                });
            }
        }

        // --- EDIT ACTION TRIGGER ---
        const editBtn = e.target.closest('.edit-btn-spa');
        if (editBtn) {
            const id = editBtn.getAttribute('data-id');
            const row = document.getElementById('row-' + id);
            
            const fname = row.querySelector('.m-name').getAttribute('data-fname');
            const lname = row.querySelector('.m-name').getAttribute('data-lname');
            const dob = row.querySelector('.m-dob').innerText;
            const gender = row.querySelector('.m-gender').innerText;
            const status = row.querySelector('.m-status-text').innerText;
            const notes = row.querySelector('.m-notes').innerText;

            document.getElementById('edit_id').value = id;
            document.getElementById('edit_fname').value = fname;
            document.getElementById('edit_lname').value = lname;
            document.getElementById('edit_dob').value = dob;
            document.getElementById('edit_gender').value = gender;
            document.getElementById('edit_status').value = status;
            document.getElementById('edit_notes').value = notes;

            const myModal = new bootstrap.Modal(document.getElementById('editModal'));
            myModal.show();
        }
    });

    function reinitializePageScripts(container) {
        const scripts = container.querySelectorAll('script');
        scripts.forEach(oldScript => {
            const newScript = document.createElement('script');
            Array.from(oldScript.attributes).forEach(attr => newScript.setAttribute(attr.name, attr.value));
            newScript.appendChild(document.createTextNode(oldScript.innerHTML));
            oldScript.parentNode.replaceChild(newScript, oldScript);
        });
    }
});