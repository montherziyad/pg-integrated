

import Alpine from 'alpinejs';

// initSelect helper for assignment UI — available before Alpine starts
window.initSelect = function (inputName, initial = []) {
    return {
        query: '',
        results: [],
        selected: initial || [],
        name: inputName,
        async search() {
            if (this.query.length < 2) { this.results = []; return; }
            try {
                const res = await fetch('/users/search?q=' + encodeURIComponent(this.query), { credentials: 'same-origin' });
                this.results = await res.json();
            } catch (e) {
                console.error('User search failed', e);
                this.results = [];
            }
        },
        select(user) {
            if (!this.selected.find(u => u.id === user.id)) this.selected.push(user);
            this.query = '';
            this.results = [];
        },
        remove(user) { this.selected = this.selected.filter(u => u.id !== user.id); }
    };
};

window.Alpine = Alpine;

Alpine.start();
