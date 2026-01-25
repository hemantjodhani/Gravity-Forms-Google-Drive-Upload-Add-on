document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".gfgd-upload-container").forEach(function (container) {

        const dropZone     = container.querySelector(".gfgd-drop-zone");
        const fileInput    = container.querySelector(".gfgd-drop-zone__input");
        const fileDetails  = container.querySelector(".gfgd-file-details");
        const filesList    = container.querySelector(".gfgd-files-list");
        const content      = container.querySelector(".gfgd-drop-zone__content");
        const clearBtn     = container.querySelector(".gfgd-clear-btn");
        
        if (!dropZone || !fileInput) return;

        // Prevent opening file browser if "Clear" button is clicked
        dropZone.addEventListener("click", (e) => {
            if (e.target.classList.contains('gfgd-clear-btn')) return;
            fileInput.click();
        });

        fileInput.addEventListener("change", () => {
            if (fileInput.files.length) {
                displayFileDetails(fileInput.files);
            }
        });

        dropZone.addEventListener("dragover", (e) => {
            e.preventDefault();
            dropZone.classList.add("gfgd-drop-zone--over");
        });

        ["dragleave", "dragend"].forEach(type => {
            dropZone.addEventListener(type, () => {
                dropZone.classList.remove("gfgd-drop-zone--over");
            });
        });

        dropZone.addEventListener("drop", (e) => {
            e.preventDefault();
            if (e.dataTransfer.files.length) {
                const dataTransfer = new DataTransfer();
                Array.from(e.dataTransfer.files).forEach(file => {
                    dataTransfer.items.add(file);
                });
                fileInput.files = dataTransfer.files;
                displayFileDetails(fileInput.files);
            }
            dropZone.classList.remove("gfgd-drop-zone--over");
        });

        if (clearBtn) {
            clearBtn.addEventListener("click", function (e) {
                e.preventDefault();
                e.stopPropagation();
                clearFiles();
            });
        }

        function formatFileSize(bytes) {
            if (!bytes) return "0 Bytes";
            const k = 1024;
            const sizes = ["Bytes", "KB", "MB"];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + " " + sizes[i];
        }

        function displayFileDetails(files) {
            content.style.display = "none";
            filesList.innerHTML = "";
            Array.from(files).forEach(file => {
                const fileItem = document.createElement("div");
                fileItem.className = "gfgd-file-item";
                fileItem.innerHTML = `
                    <div class="gfgd-file-item__name" style="font-weight:bold; margin-bottom:5px;">${file.name}</div>
                    <div class="gfgd-file-item__meta" style="font-size:12px; color:#666;">
                        <span>📦 ${formatFileSize(file.size)}</span>
                    </div>
                `;
                filesList.appendChild(fileItem);
            });
            fileDetails.style.display = "block";
        }

        function clearFiles() {
            fileInput.value = "";
            fileInput.files = new DataTransfer().files;
            fileDetails.style.display = "none";
            filesList.innerHTML = "";
            content.style.display = "flex";
        }
    });
});