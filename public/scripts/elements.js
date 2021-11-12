class Authors extends HTMLElement {

    constructor() {
        super();
        this.attachShadow({mode: 'open'});
        this.shadowRoot.innerHTML = `
            <style>
                :host {
                    display: block;
                }
                header {
                    position: relative;
                }
                ul {
                    list-style: none;
                    padding: 0;
                }
                .search-result {
                    position: absolute;
                    z-index: 1;
                    width: 100%;
                    background-color: white;
                    margin: 0;
                    padding: 0;
                    list-style: none;
                    box-shadow:
                        0 1px 1px hsl(0deg 0% 0% / 0.075),
                        0 2px 2px hsl(0deg 0% 0% / 0.075),
                        0 4px 4px hsl(0deg 0% 0% / 0.075),
                        0 8px 8px hsl(0deg 0% 0% / 0.075),
                        0 16px 16px hsl(0deg 0% 0% / 0.075)
                    ;
                }
                .search-result__item {
                    padding: 0 1rem;
                    margin: 1rem 0;
                    cursor: pointer;
                }
                .search-result--button {
                    padding-top: 1rem;
                    border-top: 1px solid var(--color-shade-accend);
                }
            </style>
            <template id="search-result-item">
                <li class="search-result__item"></li>
            </template>
            <template id="author-list-item">
                <li draggable="true">
                    <input type="hidden" name="author[]">
                    <a href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon--text" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    </a>
                    <span></span>
                </li>
            </template>
            <section>
                <header>
                    <input type="search" placeholder="Search an Author" />
                    <ul class="search-result" data-search-result></ul>
                </header>
                <ul>
                <slot></slot>
                </ul>
            </section>
        `;
        this.handleSearch = this.handleSearch.bind(this);
        this.createSearchResultItem = this.createSearchResultItem.bind(this);
        this.createListItem = this.createListItem.bind(this);
        this.attachListItem = this.attachListItem.bind(this);
        this.createEntry = this.createEntry.bind(this);
    }

    static get observedAttributes() { return ['url']; }

    connectedCallback() {
        this.shadowRoot.querySelector('input').addEventListener('input', this.handleSearch);
        this.shadowRoot.querySelector('slot').addEventListener('slotchange', () => {
            Array.from(this.children).filter(item => (
                item.nodeType === this.ELEMENT_NODE &&
                item.dataset.controlled === undefined &&
                item.dataset.entry !== undefined
            )).forEach(item => {
                item.dataset.controlled = true;
                item.draggable = true;
                item.querySelector('a').addEventListener('click', event => {
                    event.preventDefault();
                    item.parentElement.removeChild(item);
                });
                item.addEventListener('dragstart', (event) => {
                    this.drag = event.srcElement;
                    event.dataTransfer.setData("text/plain", event.target.id);
                    event.dataTransfer.dropEffect = "copy";
                });
            });
        });
        this.addEventListener('drop', event => {
            event.preventDefault();
            this.drag = undefined;
        });
        this.addEventListener('dragover', event => {
            event.preventDefault();

            const candidate = Array.from(this.children)
                .filter(item => item !== this.drag)
                .reduce((closest, child) => {
                    const box = child.getBoundingClientRect();
                    const offset = event.clientY - box.top - box.height / 2;
                    if (offset < 0 && offset > closest.offset) {
                        return { offset: offset, element: child };
                    } else {
                        return closest;
                    }
                }, { offset: Number.NEGATIVE_INFINITY, element: undefined });

            if (!candidate.element) {
                this.appendChild(this.drag);
            } else {
                this.insertBefore(this.drag, candidate.element);
            }
        });
    }

    async handleSearch(event) {
        const searchResultElement = this.shadowRoot.querySelector('[data-search-result]');
        const searchResultTemplate = this.shadowRoot.getElementById('search-result-item');
        const authorItemTemplate = this.shadowRoot.getElementById('author-list-item');

        const value = event.target.value;

        const response = await fetch(`${this.getAttribute('url')}?q=${value}`);
        const items = await response.json();

        searchResultElement && (searchResultElement.innerText = '');

        const searchResultElements = items.map(item => {
            return this.createSearchResultItem(item, searchResultTemplate, () => {
                this.attachListItem(this.createListItem(item, authorItemTemplate));
                searchResultElement && (searchResultElement.innerText = '');
                event.target.value = '';
            });
        });

        const createNewElement = document.createElement('li');
        createNewElement.classList.add('search-result__item');
        createNewElement.classList.add('search-result--button');
        createNewElement.innerText = 'New Entry';
        createNewElement.addEventListener('click', async () => {
            searchResultElement && (searchResultElement.innerText = '');
            event.target.value = '';
            const json = await this.createEntry(value);
            this.attachListItem(this.createListItem(json, authorItemTemplate));
        });
        searchResultElements.push(createNewElement);

        searchResultElements.forEach(element => searchResultElement.appendChild(element));
    }

    createSearchResultItem(item, template, onClick) {
        const clone = template.content.cloneNode(true);
        const searchResultListItemElement = clone.querySelector('li');
        searchResultListItemElement.innerHTML = item.name;
        searchResultListItemElement.addEventListener('click', onClick);
        return searchResultListItemElement;
    }

    createListItem(item, template) {
        const authorItemCloneElement = template.content.cloneNode(true);
        authorItemCloneElement.querySelector('li').dataset.entry = true;
        authorItemCloneElement.querySelector('input').value = item.id;
        authorItemCloneElement.querySelector('span').innerText = item.name;
        return authorItemCloneElement;
    }

    attachListItem(item) {
        this.appendChild(item);
        return item;
    }

    async createEntry(value) {
        const form = new FormData();
        form.append('name', value);
        const response = await fetch('/update/author', {
            method: 'POST',
            body: form,
            headers: {'X-REQUESTED-WITH': 'xmlhttprequest'}
        });
        return await response.json();
    }
}
window.customElements.define('x-authors', Authors);


class ImageUpload extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({mode: 'open'});
        this.shadowRoot.innerHTML = `
            <style>
                .add {
                    height: 100px;
                    width: 100px;
                    display: flex;
                    align-items: center;
                    justify-content: space-around;
                    background-color: var(--color-shade-accend);
                    cursor: pointer;
                }
                .icon {}
                .icon--text {
                    height: 1rem;
                    width: auto;
                    vertical-align: bottom;
                    margin: 0 0.4rem 0 0;
                }
            </style>
            <template id="image-preview-template">
                <li draggable="true" data-entry="img" class="image-list__item">
                <figure class="image-list__figure">
                    <img class="image-list__image" draggable="false" slot="icon" style="object-fit: cover" height="100" width="100" />
                    <figcaption class="image-list__caption">
                        <input type="hidden">
                        <a href="#" data-edit>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon--text" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                            </svg>
                        </a>
                        <a href="#" data-delete>
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon--text" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </a>
                    </figcaption>
                </figure>
                <dialog>
                    <textarea></textarea>
                    <button data-cancel>Cancel</button>
                    <button data-save>Save</button>
                </dialog>
                </li>
            </template>
            <slot></slot>
            <li class="add">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon--text" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
            </li>
        `;
        this.handleUpload = this.handleUpload.bind(this);
        this.edit = this.edit.bind(this);
        this.upload = this.upload.bind(this);
        this.initialized = false;
    }

    handlerFunction(event) {
        event.preventDefault();
        event.stopPropagation();
    }

    handleUpload(event) {
        [...event.dataTransfer.files].forEach(this.upload)
    }

    async upload(file) {
        let formData = new FormData();
        const placeholder = document.createElement('li');
        placeholder.innerText = 'loading...';
        this.appendChild(placeholder);

        formData.append('file', file)

        const response = await fetch(this.getAttribute('upload-url'), {
            method: 'POST',
            body: formData
        });

        if (response.status > 299) {
            placeholder.innerText = 'Error...';
            return;
        }

        const json = await response.json();

        const previewTemplate = this.shadowRoot.getElementById('image-preview-template');
        const previewElement = previewTemplate.content.cloneNode(true);
        previewElement.querySelector('li').dataset.entry = json[0].id;
        previewElement.querySelector('img').src = json[0].thumb;
        previewElement.querySelector('input').value = json[0].id;
        previewElement.querySelector('input').name = this.getAttribute('type');

        placeholder.parentElement.replaceChild(previewElement, placeholder);
    }

    connectedCallback() {
        !this.hasAttribute('upload-url') && this.setAttribute('upload-url', '/image');

        this.shadowRoot.querySelector('slot').addEventListener('slotchange', () => {
            Array.from(this.children).filter(child => (
                child.nodeType === this.ELEMENT_NODE &&
                child.dataset.entry !== undefined &&
                child.dataset.controlled === undefined
            )).forEach(child => {
                child.dataset.controlled = true;
                child.querySelector('[data-edit]').addEventListener('click', (event) => {
                    event.preventDefault();
                    child.querySelector('dialog').showModal();
                })
                child.querySelector('[data-delete]').addEventListener('click', (event) => {
                    event.preventDefault();
                    child.parentElement.removeChild(child);
                })
                child.querySelector('[data-cancel]').addEventListener('click', (event) => {
                    event.preventDefault();
                    child.querySelector('dialog').close();
                });
                child.querySelector('[data-save]').addEventListener('click', event => {
                    event.preventDefault();
                    child.querySelector('dialog').close();
                    this.edit(
                        child.dataset.entry,
                        child.querySelector('textarea').value
                    );
                })
            });
        })
        this.addEventListener('dragenter', this.handlerFunction, false);
        this.addEventListener('dragleave', this.handlerFunction, false);
        this.addEventListener('dragover', this.handlerFunction, false);
        this.addEventListener('drop', this.handlerFunction, false);
        this.addEventListener('drop', this.handleUpload, false);

        this.addEventListener('dragleave', () => this.style.backgroundColor = 'transparent', false)
        this.addEventListener('drop', () => this.style.backgroundColor = 'transparent', false)
        this.addEventListener('dragover', () => this.style.backgroundColor = 'pink', false)

        this.shadowRoot.querySelector('li').addEventListener('click', () => {
            const input = document.createElement('input');
            input.type = 'file';
            input.style.position = 'absolute';
            input.style.top = '-100px';
            input.addEventListener('input', (event) => {
                [...event.target.files].forEach(this.upload)
            });
            document.body.appendChild(input);
            input.click();

        });
    }

    async edit(id, value) {
        const form = new FormData();
        form.append('description', value);

        fetch(`/update/image/${id}`, {
            method: 'POST',
            body: form
        });
    }
}
window.customElements.define('x-upload', ImageUpload);


class Search extends HTMLElement {
    constructor() {
        super();
        this.attachShadow({mode: 'open'});
        this.shadowRoot.innerHTML = `
            <style>
                div {position: relative;}
                ul {position: absolute;}
            </style>
            <template id="search-result-template">
                <img width="50" height="50" />
                <a></a>
                <ul></ul>
            </template>
            <div>
            <input type="search" />
            <ul data-search-result-list></ul>
            </div>
        `;

        this.search = this.throttle(this.search.bind(this), 1000);
    }

    search(value) {
        const searchResultListElement = this.shadowRoot.querySelector('[data-search-result-list]');
            fetch(`/api/search?q=${value}`)
                .then(response => response.json())
                .then(list => {
                    searchResultListElement.innerText = '';
                    list.results.map(item => {
                        const option = this.shadowRoot.getElementById('search-result-template').content.cloneNode(true);
                        const aotorListElement = option.querySelector('ul')
                        option.querySelector('a').setAttribute('href', `/update/entry/${item.id}`);
                        option.querySelector('a').innerText = item.title;
                        if (item.poster) {
                            option.querySelector('img').src = `/img/50x50/${item.poster.name}`;
                        }
                        item.authors.forEach(author => {
                            const authorListItemElement = document.createElement('li');
                            authorListItemElement.innerText = author.name;
                            aotorListElement.appendChild(authorListItemElement);
                        })
                        searchResultListElement.appendChild(option);
                    })
                });
    }

    connectedCallback() {
        this.shadowRoot.querySelector('input').addEventListener('input', event => {
            event.preventDefault();
            this.search(event.target.value);
        });
    }

    throttle(func, wait, options) {
        var context, args, result;
        var timeout = null;
        var previous = 0;
        if (!options) options = {};
        var later = function() {
            previous = options.leading === false ? 0 : Date.now();
            timeout = null;
            result = func.apply(context, args);
            if (!timeout) context = args = null;
        };
        return function() {
            var now = Date.now();
            if (!previous && options.leading === false) previous = now;
            var remaining = wait - (now - previous);
            context = this;
            args = arguments;
            if (remaining <= 0 || remaining > wait) {
                if (timeout) {
                    clearTimeout(timeout);
                    timeout = null;
                }
                previous = now;
                result = func.apply(context, args);
                if (!timeout) {
                    context = args = null;
                }
            } else if (!timeout && options.trailing !== false) {
                timeout = setTimeout(later, remaining);
            }
            return result;
        };
    };
}

window.customElements.define('x-search', Search);
