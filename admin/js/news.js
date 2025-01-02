// admin/js/news.js
class NewsManager {
    constructor() {
        this.historyStack = [];
        this.redoStack = [];
    }

    async addNews(data) {
        try {
            const response = await this.sendRequest('create', data);
            if (response.success) {
                this.historyStack.push({
                    action: 'create',
                    id: response.id,
                    data: data
                });
                this.redoStack = []; // Clear redo stack
                this.refreshList();
                showToast('News added successfully');
            }
        } catch (error) {
            showToast('Error adding news', 'error');
        }
    }

    async updateNews(id, data) {
        try {
            const response = await this.sendRequest('update', { id, ...data });
            if (response.success) {
                this.historyStack.push({
                    action: 'update',
                    id: id,
                    data: data
                });
                this.redoStack = [];
                this.refreshList();
                showToast('News updated successfully');
            }
        } catch (error) {
            showToast('Error updating news', 'error');
        }
    }

    async deleteNews(id) {
        if (confirm('Are you sure you want to delete this news?')) {
            try {
                const response = await this.sendRequest('delete', { id });
                if (response.success) {
                    this.historyStack.push({
                        action: 'delete',
                        id: id
                    });
                    this.redoStack = [];
                    this.refreshList();
                    showToast('News deleted successfully');
                }
            } catch (error) {
                showToast('Error deleting news', 'error');
            }
        }
    }

    async undo() {
        if (this.historyStack.length > 0) {
            const action = this.historyStack.pop();
            try {
                const response = await this.sendRequest('undo', { 
                    history_id: action.id 
                });
                if (response.success) {
                    this.redoStack.push(action);
                    this.refreshList();
                    showToast('Action undone successfully');
                }
            } catch (error) {
                this.historyStack.push(action); // Restore action if undo fails
                showToast('Error undoing action', 'error');
            }
        }
    }

    async redo() {
        if (this.redoStack.length > 0) {
            const action = this.redoStack.pop();
            try {
                let response;
                switch (action.action) {
                    case 'create':
                        response = await this.sendRequest('create', action.data);
                        break;
                    case 'update':
                        response = await this.sendRequest('update', {
                            id: action.id,
                            ...action.data
                        });
                        break;
                    case 'delete':
                        response = await this.sendRequest('delete', {
                            id: action.id
                        });
                        break;
                }
                if (response.success) {
                    this.historyStack.push(action);
                    this.refreshList();
                    showToast('Action redone successfully');
                }
            } catch (error) {
                this.redoStack.push(action); // Restore action if redo fails
                showToast('Error redoing action', 'error');
            }
        }
    }

    async sendRequest(action, data) {
        const response = await fetch('index.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                action: action,
                ...data
            })
        });
        return await response.json();
    }

    refreshList() {
        // Reload the page or update the table via AJAX
        location.reload();
    }
}

// Initialize
const newsManager = new NewsManager();

// Event Listeners
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey || e.metaKey) {
        if (e.key === 'z') {
            e.preventDefault();
            newsManager.undo();
        } else if (e.key === 'y') {
            e.preventDefault();
            newsManager.redo();
        }
    }
});
