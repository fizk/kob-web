class FileUploader extends HTMLElement {

    constructor() {
        super();

        const shadowRoot = this.attachShadow({mode: 'open'});
        shadowRoot.innerHTML = `
                        <style>
                            .container {
                                color: #212529;
                                border: 1px solid transparent;
                                padding: 0.5rem;
                            }
                            .container--active {
                                border: 1px dashed #ced4da;
                            }
                            .button {
                                color: #007bff;
                                text-decoration: none;
                            }

                            .progress {
                                display: -webkit-box;
                                display: -ms-flexbox;
                                display: flex;
                                overflow: hidden;
                                font-size: .75rem;
                                background-color: #e9ecef;
                                border-radius: .25rem;
                                height: 2px;
                            }
                            .progress-bar {
                                display: -webkit-box;
                                display: -ms-flexbox;
                                display: flex;
                                -webkit-box-orient: vertical;
                                -webkit-box-direction: normal;
                                -ms-flex-direction: column;
                                flex-direction: column;
                                -webkit-box-pack: center;
                                -ms-flex-pack: center;
                                justify-content: center;
                                color: #fff;
                                text-align: center;
                                background-color: #007bff;
                                transition: width .6s ease;
                            }
                            .progress-message {
                                font-size: 80%;
                                color: #6c757d;
                            }
                        </style>
                        <div data-container class="container">
                            <a href="#" data-button class="button">Select or drag images</a>
                            <div data-progress-bar class="progress">
                                <div class="progress-bar"></div>
                            </div>
                            <small data-progress-message class="progress-message">&nbsp;</small>
                            <slot></slot>
                        </div>
                    `;
        this.shadowRoot.querySelector('[data-button]').addEventListener('click', this.selectFile.bind(this));
        this.shadowRoot.querySelector('[data-container]').addEventListener('drop', this.onDropFile.bind(this));
        this.shadowRoot.querySelector('[data-container]').addEventListener('dragover', this.isDragOver.bind(this));
        this.shadowRoot.querySelector('[data-container]').addEventListener('dragenter', this.isDragOver.bind(this));
        this.shadowRoot.querySelector('[data-container]').addEventListener('dragleave', this.isNotDragOver.bind(this));
        this.shadowRoot.querySelector('[data-container]').addEventListener('dragend', this.isNotDragOver.bind(this));
    }

    selectFile(event) {
        event.preventDefault();
        const input = document.createElement('input');
        input.type = 'file';
        input.multiple = true;
        input.accept = 'image/*';
        input.style.position = 'absolute';
        input.style.top = '-10000px';
        input.addEventListener('change', (event) => {
            event.preventDefault();
            this.upload(event.target.files)
        });
        this.shadowRoot.appendChild(input);
        input.click();
    }

    upload(files) {
        const formData = new FormData();

        for (let i = 0; files.length > i; i++) {
            formData.append(`image${i}`, files[i]);
        }

        const progressbar = this.shadowRoot.querySelector('[data-progress-bar] > div');
        const progressmessage = this.shadowRoot.querySelector('[data-progress-message]');

        const xhr = new XMLHttpRequest();
        xhr.open('post', '/image');
        xhr.upload.addEventListener("progress", (e) => {
            progressbar.style.width = `${(e.loaded * 100.0 / e.total) || 100}%`;
        });
        xhr.upload.addEventListener("loadstart", (e) => {
            progressbar.style.width = `0%`;
            progressbar.style.backgroundColor = '#007bff';
            progressmessage.innerHTML = 'Uploading';
        });
        xhr.upload.addEventListener("loadend", (e) => {
            progressbar.style.width = `100%`;
            progressmessage.innerHTML = 'Processing images';
        });
        xhr.addEventListener('error', () => {
            progressbar.style.backgroundColor = '#d7352b';
            progressmessage.innerHTML = 'Error';
        });
        xhr.addEventListener('load', (event) => {
            if (event.target.status === 200) {
                try {
                    const result = JSON.parse(event.target.response);
                    progressbar.style.backgroundColor = '#28d776';
                    progressmessage.innerHTML = 'Done';

                    this.dispatchEvent(
                        new CustomEvent('result', {
                            detail: result
                        })
                    );
                } catch (e) {
                    progressbar.style.backgroundColor = '#d7352b';
                    progressmessage.innerHTML = 'Error';
                }
            } else {
                progressbar.style.backgroundColor = '#d7352b';
                progressmessage.innerHTML = 'Error';
            }
        });
        xhr.send(formData);
    }

    onDropFile(event) {
        const isFile = Array.from(event.dataTransfer.items).some(item => {
            return item.kind === 'file'
        });

        if (!isFile) {
            return;
        }
        event.preventDefault();
        event.stopPropagation();
        this.shadowRoot.querySelector('[data-container]').classList.remove('container--active');
        this.upload(event.dataTransfer.files);
    }

    isDragOver(event) {
        const isFile = Array.from(event.dataTransfer.items).some(item => {
            return item.kind === 'file'
        });

        if (!isFile) {
            return;
        }

        event.preventDefault();
        event.stopPropagation();
        this.shadowRoot.querySelector('[data-container]').classList.add('container--active');
    }

    isNotDragOver(event) {
        event.preventDefault();
        event.stopPropagation();
        this.shadowRoot.querySelector('[data-container]').classList.remove('container--active');
    }
}

window.customElements.define('file-uploader', FileUploader);

class ImageDisplay extends HTMLElement {
    constructor() {
        super();

        const shadowRoot = this.attachShadow({mode: 'open'});
        shadowRoot.innerHTML = `
            <style>
                :root {
                    
                }
                textarea {
                display: block;
                width: 100%;
                padding: .375rem .75rem;
                font-size: 1rem;
                line-height: 1.5;
                color: #495057;
                background-color: #fff;
                background-clip: padding-box;
                border: 1px solid #ced4da;
                border-radius: .25rem;
                transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
                overflow: auto;
                resize: vertical;
                margin: 0;
                font-family: inherit;
                }
                .container {
                    box-sizing: border-box;
                    display: flex;
                }
                .icon {
                    flex-basis: 100px;
                }
                .controls {
                    flex-grow: 1;
                }
            </style>
            <div class="container">
                <div class="icon">
                    <slot name="icon"></slot>
                </div>
                <div class="controls">
                    <textarea placeholder="Write a description..."></textarea>
                    <button>save</button>
                    <slot name="control"></slot>
                </div>
            </div>
        `;
    }

    connectedCallback() {
        const button = this.shadowRoot.querySelector('button');

        this.shadowRoot.querySelector('textarea').value = this.getAttribute('text');
        button.addEventListener('click', event => {
            event.preventDefault();
            button.setAttribute('disabled', '');
            const form = new FormData();
            form.set('description', this.shadowRoot.querySelector('textarea').value);
            const xhr = new XMLHttpRequest();
            xhr.open('POST', this.getAttribute('path'));
            xhr.addEventListener('load', e => {
                button.removeAttribute('disabled');
            });
            xhr.addEventListener('error', e => {
                button.removeAttribute('disabled');
            });
            xhr.send(form);
        })
    }

    attributeChangedCallback(attrName, oldVal, newVal) {
        switch (attrName) {
            case 'text':
                this.shadowRoot.querySelector('textarea').value = newVal;
                break;
        }
    }

    set path(path) {
        this.setAttribute('path', path);
    }

    set text(text) {
        this.setAttribute('text', text);
    }


    static get observedAttributes() {
        return ['path', 'text'];
    }
}

customElements.define('image-display', ImageDisplay);
