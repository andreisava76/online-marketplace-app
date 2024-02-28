$(document).ready(() => {

        const img_upl = document.querySelector('#image_upload');
        const preview = document.querySelector('#img-preview');
        img_upl.addEventListener('change', updateImageDisplay);

        function updateImageDisplay() {
            while (preview.firstChild) {
                preview.removeChild(preview.firstChild);
            }

            const curFiles = img_upl.files;
            if (curFiles.length >= 0) {
                const list = document.createElement('ul');
                list.classList.add('list-unstyled', 'd-flex', 'flex-wrap', 'justify-content-center')
                preview.appendChild(list);

                for (const file of curFiles) {
                    const listItem = document.createElement('li');
                    listItem.classList.add('d-inline')
                    if (validFileType(file)) {
                        const image = document.createElement('img');
                        image.classList.add('img-fluid', 'd-block', 'rounded-3', 'm-1', 'border', 'shadow-sm')
                        image.style.height = "90px";
                        image.src = URL.createObjectURL(file);
                        listItem.appendChild(image);
                    }

                    list.appendChild(listItem);
                }
            }
        }

        function validFileType(file) {
            return fileTypes.includes(file.type);
        }

        const fileTypes = [
            "image/jpeg",
            "image/png",
            "image/jpg"
        ];

    }
)
