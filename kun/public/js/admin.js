/**
 * Admin Dashboard JavaScript
 * Handles admin panel functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeAdminPanel();
    initializeDataTables();
    initializeCharts();
    initializeFormValidation();
});

/**
 * Initialize admin panel
 */
function initializeAdminPanel() {
    // Sidebar toggle
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebar = document.querySelector('.admin-sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('sidebar-collapsed', sidebar.classList.contains('collapsed'));
        });
        
        // Restore sidebar state
        const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
        }
    }

    // Active menu highlighting
    const currentPath = window.location.pathname;
    document.querySelectorAll('.admin-menu a').forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
        }
    });
}

/**
 * Initialize data tables
 */
function initializeDataTables() {
    const tables = document.querySelectorAll('.data-table');
    
    tables.forEach(table => {
        // Add sorting functionality
        const headers = table.querySelectorAll('th[data-sortable]');
        headers.forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', () => {
                sortTable(table, header);
            });
        });
        
        // Add row actions
        const actionButtons = table.querySelectorAll('.action-btn');
        actionButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const action = this.dataset.action;
                const id = this.dataset.id;
                handleRowAction(action, id);
            });
        });
    });
}

/**
 * Sort table
 */
function sortTable(table, header) {
    const index = Array.from(header.parentElement.children).indexOf(header);
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const isAscending = header.classList.contains('sort-asc');
    
    // Remove sorting classes from all headers
    table.querySelectorAll('th').forEach(th => {
        th.classList.remove('sort-asc', 'sort-desc');
    });
    
    // Sort rows
    rows.sort((a, b) => {
        const aValue = a.children[index].textContent.trim();
        const bValue = b.children[index].textContent.trim();
        
        if (!isNaN(aValue) && !isNaN(bValue)) {
            return isAscending ? bValue - aValue : aValue - bValue;
        }
        
        return isAscending 
            ? bValue.localeCompare(aValue)
            : aValue.localeCompare(bValue);
    });
    
    // Update table
    rows.forEach(row => tbody.appendChild(row));
    
    // Update header class
    header.classList.add(isAscending ? 'sort-desc' : 'sort-asc');
}

/**
 * Handle row actions
 */
function handleRowAction(action, id) {
    switch(action) {
        case 'edit':
            window.location.href = `/admin/movies/${id}/edit`;
            break;
        case 'delete':
            confirmDelete(id);
            break;
        case 'view':
            window.location.href = `/admin/movies/${id}`;
            break;
        case 'publish':
            updateStatus(id, 'published');
            break;
        case 'unpublish':
            updateStatus(id, 'draft');
            break;
    }
}

/**
 * Confirm delete
 */
function confirmDelete(id) {
    if (confirm('Are you sure you want to delete this item? This action cannot be undone.')) {
        deleteItem(id);
    }
}

/**
 * Delete item
 */
function deleteItem(id) {
    fetch(`/admin/movies/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': getCsrfToken(),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Item deleted successfully', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Failed to delete item', 'error');
        }
    })
    .catch(error => {
        console.error('Delete error:', error);
        showNotification('An error occurred', 'error');
    });
}

/**
 * Update status
 */
function updateStatus(id, status) {
    fetch(`/admin/movies/${id}/status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': getCsrfToken(),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Status updated successfully', 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Failed to update status', 'error');
        }
    })
    .catch(error => {
        console.error('Update error:', error);
        showNotification('An error occurred', 'error');
    });
}

/**
 * Initialize charts
 */
function initializeCharts() {
    // Views chart
    const viewsChart = document.getElementById('viewsChart');
    if (viewsChart) {
        createLineChart(viewsChart, {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            data: [1200, 1900, 3000, 5000, 4200, 6000]
        });
    }
    
    // Revenue chart
    const revenueChart = document.getElementById('revenueChart');
    if (revenueChart) {
        createBarChart(revenueChart, {
            labels: ['Basic', 'Standard', 'Premium'],
            data: [3500, 7500, 12000]
        });
    }
}

/**
 * Create line chart (simple implementation)
 */
function createLineChart(canvas, data) {
    const ctx = canvas.getContext('2d');
    const width = canvas.width;
    const height = canvas.height;
    const padding = 40;
    
    // Clear canvas
    ctx.clearRect(0, 0, width, height);
    
    // Draw axes
    ctx.strokeStyle = '#2a2a2a';
    ctx.lineWidth = 2;
    ctx.beginPath();
    ctx.moveTo(padding, padding);
    ctx.lineTo(padding, height - padding);
    ctx.lineTo(width - padding, height - padding);
    ctx.stroke();
    
    // Draw data
    const maxValue = Math.max(...data.data);
    const xStep = (width - 2 * padding) / (data.labels.length - 1);
    const yScale = (height - 2 * padding) / maxValue;
    
    ctx.strokeStyle = '#e50914';
    ctx.lineWidth = 3;
    ctx.beginPath();
    
    data.data.forEach((value, i) => {
        const x = padding + i * xStep;
        const y = height - padding - value * yScale;
        
        if (i === 0) {
            ctx.moveTo(x, y);
        } else {
            ctx.lineTo(x, y);
        }
        
        // Draw point
        ctx.fillStyle = '#e50914';
        ctx.fillRect(x - 3, y - 3, 6, 6);
    });
    
    ctx.stroke();
}

/**
 * Create bar chart (simple implementation)
 */
function createBarChart(canvas, data) {
    const ctx = canvas.getContext('2d');
    const width = canvas.width;
    const height = canvas.height;
    const padding = 40;
    
    const maxValue = Math.max(...data.data);
    const barWidth = (width - 2 * padding) / data.labels.length - 20;
    const yScale = (height - 2 * padding) / maxValue;
    
    data.data.forEach((value, i) => {
        const x = padding + i * ((width - 2 * padding) / data.labels.length) + 10;
        const barHeight = value * yScale;
        const y = height - padding - barHeight;
        
        ctx.fillStyle = '#e50914';
        ctx.fillRect(x, y, barWidth, barHeight);
        
        // Draw label
        ctx.fillStyle = '#fff';
        ctx.font = '12px Arial';
        ctx.textAlign = 'center';
        ctx.fillText(data.labels[i], x + barWidth / 2, height - padding + 20);
    });
}

/**
 * Initialize form validation
 */
function initializeFormValidation() {
    const forms = document.querySelectorAll('.admin-form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
                showNotification('Please fill in all required fields', 'error');
            }
        });
        
        // Real-time validation
        const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
        });
    });
}

/**
 * Validate form
 */
function validateForm(form) {
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!validateField(input)) {
            isValid = false;
        }
    });
    
    return isValid;
}

/**
 * Validate field
 */
function validateField(field) {
    const value = field.value.trim();
    const isValid = value !== '';
    
    if (isValid) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
    } else {
        field.classList.remove('is-valid');
        field.classList.add('is-invalid');
    }
    
    return isValid;
}

/**
 * File upload preview
 */
const fileInputs = document.querySelectorAll('input[type="file"]');
fileInputs.forEach(input => {
    input.addEventListener('change', function() {
        const file = this.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                showImagePreview(e.target.result, input);
            };
            reader.readAsDataURL(file);
        }
    });
});

/**
 * Show image preview
 */
function showImagePreview(src, input) {
    let preview = input.parentElement.querySelector('.image-preview');
    if (!preview) {
        preview = document.createElement('div');
        preview.className = 'image-preview';
        input.parentElement.appendChild(preview);
    }
    
    preview.innerHTML = `<img src="${src}" alt="Preview" style="max-width: 200px; margin-top: 10px; border-radius: 8px;">`;
}

/**
 * Bulk actions
 */
const bulkActionSelect = document.getElementById('bulkAction');
const bulkActionBtn = document.getElementById('bulkActionBtn');

if (bulkActionSelect && bulkActionBtn) {
    bulkActionBtn.addEventListener('click', () => {
        const action = bulkActionSelect.value;
        const selectedIds = getSelectedIds();
        
        if (selectedIds.length === 0) {
            showNotification('Please select at least one item', 'warning');
            return;
        }
        
        if (confirm(`Are you sure you want to ${action} ${selectedIds.length} items?`)) {
            executeBulkAction(action, selectedIds);
        }
    });
}

/**
 * Get selected IDs
 */
function getSelectedIds() {
    const checkboxes = document.querySelectorAll('.item-checkbox:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

/**
 * Execute bulk action
 */
function executeBulkAction(action, ids) {
    fetch('/admin/bulk-action', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': getCsrfToken(),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ action, ids })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(`${action} completed successfully`, 'success');
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification('Action failed', 'error');
        }
    })
    .catch(error => {
        console.error('Bulk action error:', error);
        showNotification('An error occurred', 'error');
    });
}

/**
 * Show notification
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    const colors = {
        success: '#46d369',
        error: '#e50914',
        warning: '#ffa500',
        info: '#0084ff'
    };
    
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${colors[type]};
        color: #fff;
        padding: 15px 25px;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
        z-index: 10000;
        animation: slideIn 0.3s ease;
        font-weight: 600;
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

/**
 * Get CSRF token
 */
function getCsrfToken() {
    const token = document.querySelector('meta[name="csrf-token"]');
    return token ? token.content : '';
}

/**
 * Export data
 */
function exportData(format) {
    const url = `/admin/export?format=${format}`;
    window.open(url, '_blank');
    showNotification('Export started...', 'info');
}

/**
 * Refresh statistics
 */
function refreshStats() {
    const statsCards = document.querySelectorAll('.stat-card');
    statsCards.forEach(card => {
        card.classList.add('refreshing');
    });
    
    fetch('/admin/stats/refresh')
        .then(response => response.json())
        .then(data => {
            updateStats(data);
            statsCards.forEach(card => {
                card.classList.remove('refreshing');
            });
            showNotification('Statistics refreshed', 'success');
        })
        .catch(error => {
            console.error('Refresh error:', error);
            statsCards.forEach(card => {
                card.classList.remove('refreshing');
            });
            showNotification('Failed to refresh statistics', 'error');
        });
}

/**
 * Update statistics
 */
function updateStats(data) {
    Object.keys(data).forEach(key => {
        const element = document.getElementById(`stat-${key}`);
        if (element) {
            animateValue(element, parseInt(element.textContent), data[key], 1000);
        }
    });
}

/**
 * Animate value
 */
function animateValue(element, start, end, duration) {
    const range = end - start;
    const increment = range / (duration / 16);
    let current = start;
    
    const timer = setInterval(() => {
        current += increment;
        if ((increment > 0 && current >= end) || (increment < 0 && current <= end)) {
            current = end;
            clearInterval(timer);
        }
        element.textContent = Math.round(current);
    }, 16);
}

// Add CSS for animations
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .refreshing {
        opacity: 0.5;
        pointer-events: none;
    }
    
    .is-invalid {
        border-color: #e50914 !important;
    }
    
    .is-valid {
        border-color: #46d369 !important;
    }
`;
document.head.appendChild(style);

// Log initialization
console.log('Admin.js loaded successfully! 📊');
