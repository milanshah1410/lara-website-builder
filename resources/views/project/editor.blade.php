<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Website Editor</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            display: flex;
            height: 100vh;
        }

        .sidebar {
            width: 200px;
            background: #f3f3f3;
            overflow-y: auto;
            padding: 10px;
        }

        .editor {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        textarea {
            flex: 1;
            width: 100%;
            font-family: monospace;
            padding: 10px;
        }

        iframe {
            flex: 1;
            border: none;
        }
    </style>
</head>

<body x-data="editorApp()" x-init="loadFiles()">

    <!-- Sidebar -->
    <div class="sidebar">
        <template x-for="file in files" :key="file.id">
            <div @click="selectFile(file)" style="cursor:pointer; padding:5px;" x-text="file.filename"></div>
        </template>
    </div>

    <!-- Main Editor -->
    <div class="editor">
        <h3 x-text="currentFile?.filename || 'No file selected'" style="margin: 0; padding: 10px; background: #ddd;"></h3>
        <textarea x-model="currentFile.content" @input="updatePreview"></textarea>
        <button @click="saveFile" style="padding: 10px; background: #3b82f6; color: white;">Save</button>

        <h3>Live Preview</h3>
        <iframe :src="`/preview/{{$project->id}}/${currentFile.filename}`" style="width: 100%; height: 500px; border: 1px solid #ccc;"></iframe>
    </div>

    <script>
        function editorApp() {
            return {
                files: [],
                currentFile: null,

                loadFiles() {
                    fetch('/api/projects/{{$project->id}}/files') // Replace 1 with your project ID
                        .then(res => res.json())
                        .then(data => this.files = data);
                },

                selectFile(file) {
                    this.currentFile = {
                        ...file
                    };
                },

                updatePreview() {
                    // Automatically updates iframe via :srcdoc binding
                },

                saveFile() {
                    fetch(`/api/files/${this.currentFile.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            content: this.currentFile.content
                        })
                    }).then(() => alert("Saved"));
                }
            }
        }
    </script>

</body>

</html>